<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('message')->truncate();
        DB::table('message_time')->truncate();

        for ($i = 0; $i < 100; $i++) {
            try {
                $strRandom = Str::random(100);
                echo 'message ' . $i . ' - ' . $strRandom . PHP_EOL;

                DB::table('message')->insert([
                    'message' => $strRandom,
                ]);
            } catch (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }
    }
}
