<?php

namespace App\Http\Controllers;


use App\Mail\EmailForQueuing;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{

    public function test()
    {
        Mail::to('aleksandr.kravchuk@ukr.net')
            ->send(new EmailForQueuing());

        return 'test';
    }
}
