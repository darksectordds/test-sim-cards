<?php

namespace Database\Seeders;

use App\Models\Contracts;
use App\Models\GroupSimCards;
use App\Models\SimCards;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotGeneratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query_truncate = "
            SET foreign_key_checks = 0;
            TRUNCATE `contract_clients`;
            TRUNCATE `contract_group_sim_cards`;
            TRUNCATE `contract_sim_cards`;
            TRUNCATE `group_sim_card__sim_card`;
            SET foreign_key_checks = 1;
        ";
        DB::unprepared($query_truncate);


        $contracts = Contracts::all();
        $users = User::all()->random($contracts->count());
        $group_sim_cards = GroupSimCards::all();


        foreach ($group_sim_cards as $group_sim_card) {
            $sim_card = SimCards::NotInContracts()
                ->NotInGroupSimCards()
                ->first();

            $group_sim_card->sim_cards()->attach($sim_card);
        }

        // поскольку групп меньше чем контрактов, а на контракте есть
        // выбор привязать либо 1 сим-карту, либо группу сим-карт, то
        // используем генератор, чтобы привязать к группе неиспользуемые
        // сим-карты, чтобы потом привязать к контракту
        $funcGetGroupGenerator = function () use($group_sim_cards) {
            foreach ($group_sim_cards as $group_sim_card) {

                // генерируем количество сим-карт, которые
                // будем привязывать к нашей выбранной группе
                $count = rand(1, 10);
                for($idx = 0; $idx <= $count; ++$idx) {
                    // ищем не используемую сим-карту
                    $sim_card = SimCards::NotInContracts()
                        ->NotInGroupSimCards()
                        ->first();

                    // привязываем к группе
                    $group_sim_card->sim_cards()->attach($sim_card);
                }

                // возвращаем текущую группу из генератора
                yield $group_sim_card;
            }
        };

        $group = $funcGetGroupGenerator();
        foreach ($contracts as $idx=>$contract) {
            $user = $users[$idx];

            // привязываем к контракту 1-го пользователя
            $contract->clients()->attach($user);

            // привязываем группу сим-карт
            if ($group->valid()) {
                $contract->group_sim_cards()->attach($group->current());
                $group->next();
            }
            // привязываем одиночную сим-карту
            else {
                $sim_card = SimCards::NotInContracts()
                    ->NotInGroupSimCards()
                    ->first();

                $contract->sim_cards()->attach($sim_card);
            }
        }
    }
}
