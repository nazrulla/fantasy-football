<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class ViewController extends Controller
{
  public function main()
  {
    $user = auth()->user();
    return new UserResource($user->load('team.players.role', 'team.country', 'team.players.country'));
  }
}
