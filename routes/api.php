<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('user')->group(function (){
  Route::post('register', 'User\AuthController@register');
  Route::get('verify/{id}', 'User\AuthController@verify')->name('verification.verify');
  Route::post('resend', 'User\AuthController@resend')->name('verification.resend');
  Route::post('login', 'User\AuthController@login');
  
  Route::prefix('main')->middleware('auth')->group(function (){
    Route::get('/', 'User\MainController@main');
    Route::get('transfers', 'User\MainController@transfers');
    Route::post('updatePlayer/{player}', 'User\MainController@updatePlayer');
    Route::post('updateTeam/{team}', 'User\MainController@updateTeam');
    Route::post('transfer/{player}', 'User\MainController@transfer');
    Route::post('buy/{player}', 'User\MainController@buy');
  });
});

Route::prefix('admin')->group(function (){
  Route::post('login', 'Admin\MainController@login');
  Route::apiResources([
    'users' => 'Admin\UserController',
    'players' => 'Admin\PlayerController',
    'teams' => 'Admin\TeamController',
    'transfers' => 'Admin\TransferController', 
  ],
  ['middleware' => 'auth:admin']);
});