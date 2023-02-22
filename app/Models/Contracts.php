<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /*
     |---------------------------------------------------------------------------------------------
     | Extend Attrs
     |--------------------------------------------------------------------------------------
     */

    /**
     * Получение клиента текущего контракта
     *
     * @return null|User
     */
    public function getClientAttribute()
    {
        return $this->clients->first();
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Scope
     |--------------------------------------------------------------------------------------
     */

    /**
     * Query: принадлежит пользователю
     *
     * @param $query
     * @param $user_id
     */
    public function scopeBelongsUser($query, $user_id)
    {
        $query->whereHas('clients', function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        });
    }

    /**
     * Query: фильтр по названию
     *
     * @param $query
     * @param $name
     */
    public function scopeHasName($query, $name)
    {
        $query->where('name', 'LIKE', "%$name%");
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------------------
     */

    /**
     * К контракту клиент через Pivot-таблицу
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(User::class, 'contract_clients',
            'contract_id', 'user_id');
    }
    
    /**
     * К контракту множество SIM-карт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sim_cards()
    {
        return $this->belongsToMany(SimCards::class, 'contract_sim_cards',
            'contract_id', 'sim_card_id');
    }

    /**
     * К контракту множество групп SIM-карт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function group_sim_cards()
    {
        return $this->belongsToMany(GroupSimCards::class, 'contract_group_sim_cards',
            'contract_id', 'group_sim_card_id');
    }
}
