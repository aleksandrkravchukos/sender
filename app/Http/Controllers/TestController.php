<?php

namespace App\Http\Controllers;


use App\Mail\EmailForQueuing;
use App\MessageTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    public function test()
    {

        $messagesTime = MessageTime::where('start_time', '<=', Carbon::now()->toDateTimeString())->limit(2)->get();

        foreach ($messagesTime as $oneRow) {
            $realMessage = $oneRow->message->message;

            Mail::to('leos2000@gmail.com')
                ->send(new EmailForQueuing(strval($realMessage)));
        }

    }
}
