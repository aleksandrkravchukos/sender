<?php

namespace Tests\Unit;

use App\Client;
use App\Mail\EmailForQueuing;
use App\Message;
use App\MessageTime;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;

use App\Services\Sender;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SenderTest extends TestCase
{
    use DatabaseTransactions;

    private MessageRepositoryInterface $repositoryStub;

    /**
     * @var Sender
     */
    private $sender;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);


        $repositoryMockData = [
            (object)[
                'id' => 41,
                'message' => 'Message1',
                'created_at' => '2020-07-05 21:03:59',
                'updated_at' => '2020-07-05 21:03:59',
                'message_id' => 41,
                'start_time' => '10:00',
                'message_time_id' => 41,
                'timezone_shift' => 2,
                'time_in_timezome' => '10:00',
                'name' => 'Client1',
                'email' => 'client1@gmail.com',
                'time_zone' => 2
            ],

            (object)[
                'id' => 42,
                'message' => 'Message2',
                'created_at' => '2020-07-05 21:03:59',
                'updated_at' => '2020-07-05 21:03:59',
                'message_id' => 42,
                'start_time' => '09:00',
                'message_time_id' => 42,
                'timezone_shift' => 3,
                'time_in_timezome' => '10:00',
                'name' => 'Client2',
                'email' => 'client2@gmail.com',
                'time_zone' => 3
            ]
        ];

        $repositoryStub = $this->createMock(MessageRepositoryInterface::class);

        $repositoryStub
            ->method("getMessagesForSendByTime")
            ->willReturn($repositoryMockData);

        $this->sender = new Sender($repositoryStub);

    }

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function successAddMinuteEmailsToQueue()
    {
        Mail::fake();

        $this->sender->addMinuteEmailsToQueue('10:00');

        Mail::assertQueued(EmailForQueuing::class,2);

        Mail::assertQueued(EmailForQueuing::class, function(EmailForQueuing $mail) {
            switch($mail->message) {
                case 'Message1':
                    $this->assertEquals('client1@gmail.com', $mail->to[0]['address']);
                    break;
                case 'Message2':
                    $this->assertEquals('client2@gmail.com', $mail->to[0]['address']);
                    break;
            }
            return true;
        });
    }
}
