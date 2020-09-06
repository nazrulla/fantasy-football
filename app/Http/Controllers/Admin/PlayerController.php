<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use App\Models\Transfer;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Player::all();
        return PlayerResource::collection($players->load('country', 'team', 'role'));
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
            'name' => 'required',
            'lname' => 'required',
            'country_id' => 'required|exists:countries,id',
            'age' => 'required|integer|min:18|max:40',
            'value' => 'required|integer|min:0',
            'role_id' => 'required|exists:roles,id',
            'team_id' => 'exists:teams,id'
        ]);
        $player = Player::create($request->all());
        if($request->has('team_id')){
            $team = Team::find($request->team_id);
            $team->updateValue();
        }
        return new PlayerResource($player->fresh()->load('team', 'country', 'role'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return new PlayerResource($player->load('team', 'role', 'country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        $request->validate([
            'country_id' => 'exists:countries,id',
            'age' => 'integer|min:18|max:40',
            'value' => 'integer|min:0',
            'role_id' => 'exists:roles,id',
            'team_id' => 'exists:teams,id'
        ]);
        $prev_team = $player->team;
        $player->update($request->all());
        $player->refresh();
        if($request->has('team_id')){
            if($prev_team != null)
                $prev_team->updateValue();
            $player->team->updateValue();
        }
        return new PlayerResource($player->load('team', 'country', 'role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->delete();
        Transfer::where('player_id', $player->id)->delete();
        $player->team->updateValue();
        return $player->id;
    }
}
