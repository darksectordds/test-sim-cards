<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSimCards extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /*
     |---------------------------------------------------------------------------------------------
     | Scope
     |--------------------------------------------------------------------------------------
     */

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
     * К одной группе множество SIM-карт
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sim_cards()
    {
        return $this->belongsToMany(SimCards::class, 'group_sim_card__sim_card',
            'group_sim_card_id', 'sim_card_id');
    }
}
