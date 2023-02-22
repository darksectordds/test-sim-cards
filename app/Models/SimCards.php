<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimCards extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'IMSI',
    ];

    /*
     |---------------------------------------------------------------------------------------------
     | Scope
     |--------------------------------------------------------------------------------------
     */

    /**
     * Query: сим-карта не используется в контрактах
     *
     * @param $query
     */
    public function scopeNotInContracts($query)
    {
        $query->doesntHave('contracts');
    }

    /**
     * Query: сим-карта не принадлежит группе
     *
     * @param $query
     */
    public function scopeNotInGroupSimCards($query)
    {
        $query->doesntHave('group_sim_cards');
    }

    /**
     * Query: фильтр по номеру сим-карты
     *
     * @param $query
     * @param $number
     */
    public function scopeHasNumber($query, $number)
    {
        $query->where('number', 'LIKE', "%$number%");
    }

    /**
     * Query: фильтр принадлежит пользователю
     *
     * @param $query
     * @param $user_id
     */
    public function scopeBelongsUserByContract($query, $user_id)
    {
        $query->whereHas('contracts', function ($query) use($user_id) {
            $query->BelongsUser($user_id);
        });
    }

    /**
     * Query: фильтр по названию контрактов
     *
     * @param $query
     * @param $name
     */
    public function scopeHasContractWithName($query, $name)
    {
        $query->whereHas('contracts', function ($query) use($name) {
            $query->HasName($name);
        });
    }

    /**
     * Query: фильтр по названию групп сим-карт
     *
     * @param $query
     * @param $name
     */
    public function scopeHasGroupSimCardWithName($query, $name)
    {
        $query->whereHas('group_sim_cards', function ($query) use($name) {
            $query->HasName($name);
        });
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------------------
     */

    /**
     * Контракты сим-карт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contracts()
    {
        return $this->belongsToMany(Contracts::class, 'contract_sim_cards',
            'sim_card_id', 'contract_id');
    }

    /**
     * Группы сим-карт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function group_sim_cards()
    {
        return $this->belongsToMany(GroupSimCards::class, 'group_sim_card__sim_card',
            'sim_card_id', 'group_sim_card_id');
    }
}
