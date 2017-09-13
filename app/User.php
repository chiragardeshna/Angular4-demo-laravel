<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function saveUser($data, $id = 0)
    {
        $user = $this->find($id);
        if (!$user) {
            $user = new self();
        }

        $user->fill($data);
        $user->password = bcrypt($data['password']);
        $user->save();

        return $user;
    }
}
