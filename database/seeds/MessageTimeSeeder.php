<?php

use App\Message;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $length = 100;
        for ($i = 1; $i <= $length; $i++) {
            try {
                $message = Message::where('id', '>', 0)->offset($i)->limit(1)->first();
                if ($message) {
                    $time = Carbon::now()->addMinute($i)->format('H:i');

                    echo 'Message ' . $message . ' inserted with time ' . $time;
                    DB::table('message_time')
                        ->insert([
                            'message_id' => $message->id,
                            'start_time' => $time,
                        ]);
                }
            } catch (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }
    }
}
