<?php

namespace App\Services;


use App\Mail\EmailForQueuing;
use App\Repository\MessageRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class Sender implements SenderInterface
{
    protected $response;
    protected $debug = false;

    private $messageRepository;

    private $messageToSend;

    private $mailTo;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->messageToSend = '';
        $this->mailTo = '';
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Sender email.
     */
    public function addMinuteEmailsToQueue(string $time)
    {
        $messages = $this->messageRepository->getMessagesForSendByTime($time);

        foreach ($messages as $message) {
            $this->messageToSend = $message->message;
            $subject = 'Test subject - ' . $this->messageToSend;
            $this->mailTo = $message->email;

            if (env('SEND_REAL_MESSAGE') === true) {
                Mail::to($this->mailTo)
                    ->send(new EmailForQueuing(strval($this->messageToSend), $subject));
                echo 'Mail message - ' . $this->messageToSend . ' real send to ' . $this->mailTo . PHP_EOL;
            } else {
                echo 'Mail message - ' . $this->messageToSend . ' should be send to ' . $this->mailTo . PHP_EOL;
            }
        }
    }
}
