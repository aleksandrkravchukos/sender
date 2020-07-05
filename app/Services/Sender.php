<?php

namespace App\Services;


use App\Mail\EmailForQueuing;
use App\MessageTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Sender implements SenderInterface
{
    protected $response;
    protected $debug = false;

    private $messageToSend;

    private $mailTo;

    public function __construct()
    {
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

        $messagesTime = MessageTime::where('start_time', '<=', Carbon::now()->toDateTimeString())->limit(2)->get();

        foreach ($messagesTime as $oneRow) {
            $realMessage = $oneRow->message->message;

            Mail::to('leos2000@gmail.com')
                ->queue(new EmailForQueuing(strval($realMessage)));
        }

    }
}
