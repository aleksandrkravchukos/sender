<?php

namespace App\Http\Controllers;


use App\Repository\MessageRepository;

class TestController extends Controller
{
    private $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function test()
    {

        //$messages = $this->messageRepository->getAllMessagesByTime('17:00');
        $messages = $this->messageRepository->getMessagesForSendByTime('16:10');

        dd($messages);
//
//        foreach ($messagesTime as $oneRow) {
//            $realMessage = $oneRow->message->message;
//
//            Mail::to('leos2000@gmail.com')
//                ->send(new EmailForQueuing(strval($realMessage)));
//        }

    }
}
