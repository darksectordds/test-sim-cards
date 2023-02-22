<?php

namespace Database\Seeders;

use App\Models\SimCards;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SimCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $funGenerateRandomString = function($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789-', ceil($length/strlen($x)) )),1,$length);
        };
        $timestamp = Carbon::now();

        $data = [];
        for($idx = 0; $idx <= 1000; ++$idx) {
            $item = [];
            $item['number'] = $funGenerateRandomString(8);
            $item['IMSI'] = $funGenerateRandomString(15);
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;

            $data[] = $item;
        }

        SimCards::insert($data);
    }
}
