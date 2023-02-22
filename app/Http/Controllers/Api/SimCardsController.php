<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SimCards;
use Illuminate\Http\Request;

class SimCardsController extends Controller
{
    /**
     * Список сим-карт
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $number = $request->get('number');
        $name = $request->get('name');

        $current_user = auth()->user();

        $query = SimCards::
            with([
                'contracts.clients',
            ])
            ->when(!$current_user->isAdmin(), function ($query) use($current_user) {
                // добавляем условие на то, что если `user` НЕ имеет
                // права доступа администратора, то фильтруем список
                // только по наличию сим-карт, по его контракту
                $query->BelongsUserByContract($current_user->id);
            })
            ->when($number ?? false, function ($query, $value) {
                $query->HasNumber($value);
            })
            ->when($name ?? false, function ($query, $value) use($current_user) {
                // пользователь: фильтр по группам
                if (!$current_user->isAdmin()) {
                    $query->HasGroupSimCardWithName($value);
                }
                // admin: фильтр по контрактам
                else {
                    $query->HasContractWithName($value);
                }
            });

        //dd(vsprintf(str_replace(['?'], ['\'%s\''], $query->toSql()), $query->getBindings()));

        $sim_cards = $query->paginate();

        return response()->json($sim_cards);
    }
}
