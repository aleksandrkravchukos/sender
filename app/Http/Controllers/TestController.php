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
        return 'test';
    }
}
