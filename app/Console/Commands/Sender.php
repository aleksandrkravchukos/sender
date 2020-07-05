<?php

namespace App\Console\Commands;

use App\Services\SenderInterface as SenderInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Sender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';

    /**
     * @var SenderInterface
     */
    private $senderMail;

    public function __construct(SenderInterface $senderMail)
    {
        parent::__construct();
        $this->senderMail = $senderMail;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = Carbon::now()->format('H:i');
        $this->senderMail->addMinuteEmailsToQueue($time);
    }
}
