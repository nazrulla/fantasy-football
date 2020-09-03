<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const INITIAL_BUDGET = 5000000;
    const PLAYER_LIST = [
        'goalkeeper' => 3,
        'defender' => 6, 
        'midfielder' => 6,
        'attacker' => 5
    ];
}
