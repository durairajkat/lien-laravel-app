<?php

namespace App\Policies;

use App\User as UserModel;

class SubUserPolicy
{
    /**
     * Parent can view only their own subusers
     */
    public function view(UserModel $authUser, UserModel $subUser): bool
    {
        return $subUser->parent_id === $authUser->id;
    }

    public function update(UserModel $authUser, UserModel $subUser): bool
    {
        return $subUser->parent_id === $authUser->id;
    }

    public function delete(UserModel $authUser, UserModel $subUser): bool
    {
        return $subUser->parent_id === $authUser->id;
    }
}
