<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TeamResource;
use App\Models\Player;
use App\Models\Team;
use App\Models\Country;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function main()
  {
    $user = auth()->user();
    return new UserResource($user->load('team.players.role', 'team.country', 'team.players.country'));
  }
  public function updateTeam(Request $request, Team $team)
  {
    $request->validate([
      'name' => 'max:100',
      'country' => 'exists:countries,name'
    ]);
    $user = auth()->user();
    if($user->team_id != $team->id){
      return response()->json(['message' => 'Team not found'], 400);
    }
    if($request->has('name')){
      $team->name = $request->name;
    }
    if($request->has('country')){
      $team->country()->associate(Country::where('name', $request->country)->first());
    }
    $team->save();
    return new TeamResource($team->load('country'));
  }
  public function updatePlayer(Request $request, Player $player)
  {
    $request->validate([
      'first_name' => 'regex:/^[a-zA-Z\']+$/u|max:100',
      'last_name' => 'regex:/^[a-zA-Z\']+$/u|max:100',
      'country' => 'exists:countries,name'
    ]);
    $user = auth()->user();
    if ($user->team_id != $player->team_id) {
      return response()->json(['message' => 'Player not found'], 400);
    }
    if($request->has('first_name')){
      $player->name = $request->first_name;
    }
    if($request->has('last_name')){
      $player->lname = $request->last_name;
    }
    if($request->has('country')){
      $player->country()->associate(Country::where('name', $request->country)->first());
    }
    $player->save();
    return new PlayerResource($player->load('country', 'role'));
  }
}
