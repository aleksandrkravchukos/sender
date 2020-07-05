<?php


use App\Message;
use App\MessageTime;
use App\Repository\MessageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageTimeInTimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('message_time_in_time_zone')->truncate();

        $messages = Message::all();

        $i = 0;
        foreach ($messages as $message) {
            try {
                $messageTime = MessageTime::where('message_id', $message->id)->first();

                for ($i = 0; $i < 24; $i++) {
                    $time = Carbon::createFromFormat('H:i', $messageTime->start_time)
                        ->subHour(MessageRepositoryInterface::BASE_TIME_ZONE)
                        ->addHour($i)
                        ->format('H:i');
                    echo 'Message ' . $message->message . ' start time - ' . $time . PHP_EOL;
                    DB::table('message_time_in_time_zone')
                        ->insert([
                            'message_time_id' => $messageTime->id,
                            'timezone_shift' => $i,
                            'time_in_timezome' => $time,
                        ]);
                }

            } catch (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

            $i++;
        }
    }
}
