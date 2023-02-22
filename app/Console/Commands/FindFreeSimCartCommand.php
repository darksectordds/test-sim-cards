<?php

namespace App\Console\Commands;

use App\Models\SimCards;
use Illuminate\Console\Command;

class FindFreeSimCartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:sim-card 
                            {--free : не используемые SIM-карты }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Помошник поиска SIM-карт';

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
        $free = $this->option('free');

        $sim_card = SimCards::
            when($free ?? false, function($query) {
                $query->NotInContracts()
                    ->NotInGroupSimCards();
            })
            ->get()
            ->random(1)
            ->first();

        dd($sim_card);

        return 0;
    }
}
