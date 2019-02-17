<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\CreateGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class GroupController extends Controller
{
    /**
     * Display a listing of the group.
     *
     * @return Group[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Group::all();
    }

    /**
     * Store a newly created group in storage.
     *
     * @param CreateGroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateGroupRequest $request)
    {
        $group = new Group($request->all());
        $created = $group->save();
        return Response::json(['created' => $created, 'id' => $group->id], 201);
    }

    /**
     * Display the specified group.
     *
     * @param  \App\Group $group
     * @return Group
     */
    public function show(Group $group)
    {
        return $group;
    }

    /**
     * Update the specified group in storage.
     *
     * @param CreateGroupRequest $request
     * @param  \App\Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateGroupRequest $request, Group $group)
    {
        $updated = $group->update($request->all());
        return Response::json(['updated' => $updated], 200);
    }

    /**
     * Remove the specified group from storage.
     *
     * @param  \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
