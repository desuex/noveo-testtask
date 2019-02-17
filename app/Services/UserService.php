<?php

namespace App\Services;


use App\Group;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;

class UserService
{
    /**
     * @param CreateUserRequest $request
     * @return User
     */
    public function createUser(CreateUserRequest $request)
    {
        $groupId = $request->group_id;
        $group = Group::findOrFail($groupId);
        $user = new User($request->all());
        $user->password = bcrypt($request->password);
        $user->is_active = true; //user is active by default
        $user->group()->associate($group);
        $user->save();
        return $user;
    }

    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return bool
     */
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        if ($request->group_id) {
            $group = Group::findOrFail($request->group_id);
            $user->group()->associate($group);
        }
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        if (!is_null($request->is_active)) {
            $user->is_active = (bool)$request->is_active;
        }
        return $user->update($request->all());
    }
}