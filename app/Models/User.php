<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Проверка имеет ли пользователь админ права.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin == true;
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Extend Attrs
     |--------------------------------------------------------------------------------------
     */

    /**
     * Получение контракта клиента.
     *
     * @return null|Contracts
     */
    public function getContractAttribute()
    {
        return $this->contracts->first();
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------------------
     */

    /**
     * Query: не имеет контрактов = не является клиентом.
     *
     * @param $query
     */
    public function scopeIsNotClient($query)
    {
        $query->doesntHave('contracts');
    }

    /**
     * Query: пользователь является администратором.
     *
     * @param $query
     * @param $val
     */
    public function scopeHasAdmin($query, $val = true)
    {
        $query->where('is_admin', $val);
    }

    /**
     * Query: поиск контракта по `user_id`
     *
     * @param $query
     * @param $user_id
     */
    public function scopeHasContract($query, $user_id)
    {
        $query->whereHas('contracts', function ($query) use($user_id) {
            $query->where('user_id', $user_id);
        });
    }

    /**
     * Query: поиск своего контракта
     *
     * @param $query
     */
    public function scopeHasOwnContract($query)
    {
        $user_id = auth()->id();

        $query->HasContract($user_id);
    }

    /*
     |---------------------------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------------------
     */

    /**
     * Контракт пользователя сделанный через Pivot-таблицу.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contracts()
    {
        return $this->belongsToMany(Contracts::class, 'contract_clients',
            'user_id', 'contract_id');
    }
}
