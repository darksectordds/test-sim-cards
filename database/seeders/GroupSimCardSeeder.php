<?php

namespace Database\Seeders;

use App\Models\GroupSimCards;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GroupSimCardSeeder extends Seeder
{
    protected $data = [
        ['name' => '#1-group'],
        ['name' => '#2-group'],
        ['name' => '#3-group'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        foreach($this->data as &$item) {
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }

        GroupSimCards::insert($this->data);
    }
}
