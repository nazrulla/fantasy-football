<?php

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
  
  Route::prefix('main')->middleware('verified', 'auth')->group(function (){
    Route::get('/', 'User\MainController@main');
    Route::get('transfers', 'User\MainController@transfers');
    Route::put('player/{player}', 'User\MainController@updatePlayer');
    Route::put('team', 'User\MainController@updateTeam');
    Route::post('transfer/{player}', 'User\MainController@transfer');
    Route::post('updateTransfer/{transfer}', 'User\MainController@updateTransfer');
    Route::delete('transfer/{transfer}', 'User\MainController@removeTransfer');
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