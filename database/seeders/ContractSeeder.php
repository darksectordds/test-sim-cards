<?php

namespace Database\Seeders;

use App\Models\Contracts;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    protected $data = [
        ['name' => '№1 контракт'],
        ['name' => '№2 контракт'],
        ['name' => '№3 контракт'],
        ['name' => '№4 контракт'],
        ['name' => '№5 контракт'],
        ['name' => '№6 контракт'],
        ['name' => '№7 контракт'],
        ['name' => '№8 контракт'],
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

        Contracts::insert($this->data);
    }
}
