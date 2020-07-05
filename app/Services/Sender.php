<?php

namespace App\Services;


use App\Mail\EmailForQueuing;
use App\Repository\MessageRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class Sender implements SenderInterface
{
    protected $debug = false;

    private $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Sender email.
     * @param string $time
     */
    public function addMinuteEmailsToQueue(string $time)
    {

        $messages = $this->messageRepository->getMessagesForSendByTime($time);

        foreach ($messages as $message) {

            Mail::to($message->email)
                ->queue(new EmailForQueuing(strval($message->message)));

            echo 'Mail message - ' . $message->message . ' real send to ' . $message->email . PHP_EOL;
        }
    }
}
