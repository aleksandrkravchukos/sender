<?php

namespace Tests\Unit;

use App\Client;
use App\Message;
use App\MessageTime;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseTransactions;

    private MessageRepositoryInterface $repository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->repository = new MessageRepository();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->createData();

    }

    private function createData()
    {

        $message = Message::create([
            'message' =>'Message1'
        ]);

        $time = '10:00';

        MessageTime::create(
            [
                'message_id' => $message->id,
                'start_time' => $time,
            ]
        );

        Client::create([
            'name' => 'Client1',
            'email' => 'client1@gmail.com',
            'time_zone' => '+2',
        ]);
    }

    /** @test */
    public function getAllMessagesByTime()
    {
        $result = $this->repository->getAllMessagesByTime('10:00');

        $this->assertIsArray($result);
    }
}
