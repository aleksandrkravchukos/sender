<?php declare(strict_types=1);


namespace App\Repository;

use Exception;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{

    /**
     * @param string $time
     * @return array
     */
    public function getMessagesForSendByTime(string $time): array
    {
        $result = [];

        try {
            $result = DB::table('message')
                ->join('message_time', 'message.id', '=', 'message_time.message_id')
                ->join('message_time_in_time_zone', 'message_time.id', '=', 'message_time_in_time_zone.message_time_id')
                ->join('client', 'message_time_in_time_zone.timezone_shift', '=', 'client.time_zone')
                ->where('time_in_timezome', $time)
                ->get()->toArray();

        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $result;
    }
}

