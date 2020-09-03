<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class ViewController extends Controller {
  public function main(){
    $user = auth()->user();
    return $user->load('team.players.role');
  }
}