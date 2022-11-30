<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param array $googleId
     * @param array $params
     * @return User
     */
    public function updateOrCreate(array $googleId, array $params):User
    {
        return User::updateOrCreate($googleId, $params);
    }

}

