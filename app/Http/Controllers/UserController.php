<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created user in storage.
     *
     * @param CreateUserRequest $request
     * @param UserService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request, UserService $service)
    {
        $user = $service->createUser($request);
        return Response::json(['created' => $user->wasRecentlyCreated, 'id' => $user->id], 201);

    }

    /**
     * Display the specified user.
     *
     * @param  \App\User $user
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $request
     * @param  \App\User $user
     * @param UserService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user, UserService $service)
    {
        $updated = $service->updateUser($request, $user);
        return Response::json(['updated' => $updated], 200);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
