<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Основные таблицы
         */
        $this->call(SimCardSeeder::class);
        $this->call(GroupSimCardSeeder::class);
        $this->call(ContractSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(UserSetAdminStatusSeeder::class);

        /*
         * Pivot - промежуточные таблицы
         */
        $this->call(PivotGeneratorSeeder::class);
    }
}
