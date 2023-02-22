<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FindFreeUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:user
                            { --non-client : свободного пользователя(не клиента) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Помошник по клиентам';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $non_client = $this->option('non-client');

        $user = User::when($non_client ?? false, function ($query) {
                $query->HasAdmin(false)
                    ->IsNotClient();
            })
            ->get()
            ->random(1)
            ->first();

        dd($user);

        return 0;
    }
}
