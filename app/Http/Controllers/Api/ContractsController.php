<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contracts;
use App\Models\GroupSimCards;
use App\Models\SimCards;
use App\Models\User;
use App\Rules\RuleUserIsNotAdmin;
use Illuminate\Http\Request;

class ContractsController extends Controller
{
    /**
     * Стандартные выгружаемые отношения
     *
     * @return array
     */
    private function relationsDefault()
    {
        return [
            'clients',
            'sim_cards',
            'group_sim_cards',
        ];
    }

    /**
     * Список контрактов.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $relationships = $this->relationsDefault();

        $contracts = Contracts::
            with($relationships)
            ->paginate();

        return response()->json($contracts);
    }

    /**
     * Создание нового контракта и привязка пользователя.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            // название контракта
            'name' => 'required|string|max:256',
            // привязка пользователя к контракту
            'user_id' => ['required','exists:users,id', new RuleUserIsNotAdmin()],
            // привязка сим-карты
            'sim_id' => 'required_without:group_sim_id|exists:sim_cards,id|unique:contract_sim_cards,sim_card_id',
            // привязка группы сим-карт
            'group_sim_id' => 'required_without:sim_id|exists:group_sim_cards,id|unique:contract_group_sim_cards,group_sim_card_id',
        ]);

        // WARNING:
        // ничего не известно по поводу логики работы программы и поэтому, возможно,
        // что `group_sim_id` или `sim_id` не должны быть заняты у других
        // контрактов, поэтому нужно делать доп. проверку

        $name = $request->get('name');
        $user_id = $request->get('user_id');
        $sim_id = $request->get('sim_id');
        $group_sim_id = $request->get('group_sim_id');

        $user = User::find($user_id);
        $contract = Contracts::create([
            'name' => $name,
        ]);

        // привязываем пользователя к контракту
        $contract->clients()->attach($user);

        // привязываем группу сим-карт к контракту
        if ($group_sim_id && $group_sim_card = GroupSimCards::find($group_sim_id)) {
            $contract->group_sim_cards()->attach($group_sim_card);
        }
        // привязываем 1-очную сим-карту
        else {
            $sim_card = SimCards::find($sim_id);
            $contract->sim_cards()->attach($sim_card);
        }

        return response()->json($contract->load(
            $this->relationsDefault()
        ));
    }
}
