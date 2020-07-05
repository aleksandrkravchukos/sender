<?php

namespace App\Services;


use App\Mail\EmailForQueuing;
use App\MessageTime;
use App\Repository\MessageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Sender implements SenderInterface
{
    protected $response;
    protected $debug = false;

    private $messageRepository;

    private $messageToSend;

    private $mailTo;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
        $this->messageToSend = '';
        $this->mailTo = '';
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @param int $debug
     */
    public function setDebug(int $debug)
    {
        $this->debug = $debug;
    }

    public function log(string $message)
    {
        if ($this->debug) {
            echo $message . "\n";
        }
    }

    /**
     * Sender email.
     */
    public function request()
    {

        $time = Carbon::now()->format('H:i');
        $messages = $this->messageRepository->getMessagesForSendByTime($time);
        dd($messages);
//        foreach ($messagesTime as $oneRow) {
//            $realMessage = $oneRow->message->message;
//            if (env('SEND_REAL_MESSAGE') === true) {
//                Mail::to('leos2000@gmail.com')
//                    ->send(new EmailForQueuing(strval($realMessage)));
//            }
//        }

    }
}
