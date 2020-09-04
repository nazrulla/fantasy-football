<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TeamResource;
use App\Models\Player;
use App\Models\Team;
use App\Models\Country;
use App\Models\Transfer;
use Faker;
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
    if ($user->team_id != $team->id) {
      return response()->json(['message' => 'Team not found'], 400);
    }
    if ($request->has('name')) {
      $team->name = $request->name;
    }
    if ($request->has('country')) {
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
    if ($request->has('first_name')) {
      $player->name = $request->first_name;
    }
    if ($request->has('last_name')) {
      $player->lname = $request->last_name;
    }
    if ($request->has('country')) {
      $player->country()->associate(Country::where('name', $request->country)->first());
    }
    $player->save();
    return new PlayerResource($player->load('country', 'role'));
  }
  public function transfer(Request $request, Player $player)
  {
    $request->validate([
      'price' => 'required|integer|min:0'
    ]);
    if (Transfer::where('player_id', $player->id)->exists()) {
      return response()->json([
        'message' => 'The player is already on the transfer list'
      ], 400);
    }
    $user = auth()->user();
    if ($user->team_id != $player->team_id) {
      return response()->json([
        'message' => 'Player not found'
      ], 400);
    }
    $transfer = Transfer::create([
      'player_id' => $player->id,
      'price' => $request->price
    ]);
    return $transfer;
  }
  public function buy(Request $request, Player $player){
    $faker = Faker\Factory::create();
    if(!Transfer::where('player_id', $player->id)->exists()){
      return response()->json([
        'message' => 'The player is not in the transfer list'
      ], 400);
    }
    $user = auth()->user();
    if($user->team_id == $player->team_id){
      return response()->json([
        'message' => 'You cannot buy your own player'
      ], 400);
    }
    $transfer = Transfer::where('player_id', $player->id)->first();
    if($user->team->budget < $transfer->price){
      return response()->json([
        'message' => 'You do not have enough funds to buy this player'
      ], 400);
    }
    $selling_team = $player->team;
    //Player update
    $player = $transfer->player;
    $player->team()->associate($user->team_id);
    $player->value *= 1 + $faker->numberBetween($min=10, $max=100)/100; 
    $player->save();
    //Buying Team update
    $user->team->budget -= $transfer->price;
    $user->team->updateValue();
    //Selling Team update
    $selling_team->budget += $transfer->price;
    $selling_team->updateValue();
    //Delete transfer record
    $transfer->delete();
    return $user->team;
  }
}