<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'username' => 'required',
          'password' => 'required|min:6',
          'email' => 'required|email|unique:users,email',
          'confirmed' => 'boolean',
          'team_id' => 'integer|min:1|exists:teams,id'
        ]);
        $user = User::create($request->except('confirmed'));
        if($request->has('confirmed') && $request->confirmed){
            $user->markEmailAsVerified();
        }
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user->load('team.players.country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'password' => 'min:6',
            'email' => 'email|unique:users,email',
            'confirmed' => 'boolean',
            'team_id' => 'integer|min:1|exists:teams,id'
        ]);
        $user->update($request->except('confirmed'));
        if($request->has('confirmed')){
            if($request->confirmed){
                $user->markEmailAsVerified();
            }
            else{
                $user->update(['email_verified_at' => null]);
            }
        }
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $user->id;
    }
}
