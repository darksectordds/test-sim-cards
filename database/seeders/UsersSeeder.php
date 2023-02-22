<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Замена str_shuffle под UTF-8
         *
         * @param $str
         * @return string
         */
        function str_shuffle_unicode($str) {
            $tmp = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
            shuffle($tmp);
            return join("", $tmp);
        }

        $funGenerateRandomString = function($length = 10, $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
            return mb_substr(str_shuffle_unicode(str_repeat($x=$alphabet, ceil($length/strlen($x)) )),1,$length);
        };

        $alphabet_ru = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя';
        $alphabet_en = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $timestamp = Carbon::now();

        $data = [];
        for($idx = 0; $idx <= 1000; ++$idx) {
            $item = [];
            $item['name'] = $funGenerateRandomString(rand(5, 16), $alphabet_ru);
            $item['email'] = $funGenerateRandomString(rand(5, 16), $alphabet_en).'@mail.ru';
            $item['password'] = '$2y$10$eq0whtpTxjbT7u5slGfNXOjKdzs9heQiGeyEpU3O1gzfwqFhxLwB.'; // 0000
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;

            $data[] = $item;
        }

        User::insert($data);
    }
}
