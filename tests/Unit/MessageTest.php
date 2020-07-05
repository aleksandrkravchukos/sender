<?php

namespace Tests\Unit;

use App\Client;
use App\Message;
use App\MessageTime;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
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
        $message1 = Message::create([
            'message' =>'Message1'
        ]);

        $messageTime1 = MessageTime::create(
            [
                'message_id' => $message1->id,
                'start_time' => '10:00',
            ]
        );

        $message2 = Message::create([
            'message' =>'Message2'
        ]);

        $messageTime2 = MessageTime::create(
            [
                'message_id' => $message2->id,
                'start_time' => '09:00',
            ]
        );

        Client::create([
            'name' => 'Client1',
            'email' => 'client1@gmail.com',
            'time_zone' => '2',
        ]);

        Client::create([
            'name' => 'Client2',
            'email' => 'client2@gmail.com',
            'time_zone' => '3',
        ]);

        $this->createDataMessageTimeInTimezome($messageTime1);
        $this->createDataMessageTimeInTimezome($messageTime2);
    }

    private function createDataMessageTimeInTimezome(MessageTime $messageTime) {
        for ($i = 0; $i < 24; $i++) {
            $time = Carbon::createFromFormat('H:i', $messageTime->start_time)
                ->subHour(MessageRepositoryInterface::BASE_TIME_ZONE)
                ->addHour($i)
                ->format('H:i');

            DB::table('message_time_in_time_zone')
                ->insert([
                    'message_time_id' => $messageTime->id,
                    'timezone_shift' => $i,
                    'time_in_timezome' => $time,
                ]);
        }
    }

    /** @test */
    public function getSenderInfoByTime()
    {
        $result = $this->repository->getMessagesForSendByTime('10:00');

        $resultClient1 = current(array_filter($result, fn($row) => $row->name === 'Client1'));
        $resultClient2 = current(array_filter($result, fn($row) => $row->name === 'Client2'));

        $this->assertCount(2, $result);
        $this->assertEquals('Message1', $resultClient1->message);
        $this->assertEquals('Message2', $resultClient2->message);

    }

}
