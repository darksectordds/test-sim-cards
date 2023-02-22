<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSetAdminStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = User::IsNotClient()->get()->random(2)->pluck('id');

        User::whereIn('id', $user_ids)
            ->update([
                'is_admin' => true
            ]);
    }
}
