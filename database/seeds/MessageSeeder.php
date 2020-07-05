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
        for ($i = 0; $i < 1000; $i++) {
            try {
                $strRandom = Str::random(200);
                echo 'message ' . $i . ' - ' . $strRandom . PHP_EOL;

                DB::table('messages')->insert([
                    'message' => $strRandom,
                ]);
            } catch (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }
        }
    }
}
