<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository\BaseRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\Users\CreateUserData;

use App\Entities\Models\User;
class UserRepository extends BaseRepository implements UserRepositoryInterface
{

     public function model()
    {
        return User::class;
    }

    public function create(CreateUserData $data): User
    {

        return $this->model->create($data->toArray());

    }
}
