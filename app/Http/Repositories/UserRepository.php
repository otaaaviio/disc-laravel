<?php

namespace App\Http\Repositories;

use App\interfaces\Repositories\IUserRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
