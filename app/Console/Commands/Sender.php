<?php

namespace App\Console\Commands;

use App\Services\Sender as SenderMail;
use Illuminate\Console\Command;

class Sender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = new SenderMail();
        $service->setDebug(true);
        $service->request();
    }
}
