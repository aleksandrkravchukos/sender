<?php

use App\Clients;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        for ($i = 0; $i < 5000; $i++) {
            try {
                $strRandom = Str::random(50);

                DB::table('clients')->insert([
                    'name' => $strRandom,
                    'email' => $strRandom . '@gmail.com',
                    'time_zone' => random_int(-11, 12),
                ]);

                echo 'Client ' . $i . ' - ' . $strRandom . ' created' . PHP_EOL;
            } catch (Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

        }
    }
}
