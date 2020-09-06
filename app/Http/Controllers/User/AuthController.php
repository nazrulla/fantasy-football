<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Team;
use App\Models\Country;
use App\Models\Player;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required|confirmed|min:6',
      'email' => 'required|email|unique:users,email',
    ]);
    $user = User::create($request->except('password_confirmation'))->sendEmailVerificationNotification();
    return response()->json(['message' => __('auth.email_success')], 200);
  }
  public function verify(Request $request, $user_id)
  {
    if (!$request->hasValidSignature()) {
      return response()->json(['message' => 'Invalid/Expired url provided.'], 401);
    }
    $user = User::findOrFail($user_id);

    if (!$user->hasVerifiedEmail()) {
      $user->markEmailAsVerified();
      $team = Team::create([
        'name' => $user->username . '-team',
        'country_id' => Country::inRandomOrder()->first()->getKey(),
        'budget' => Controller::INITIAL_BUDGET,
      ]);
      $user->team()->associate($team)->save();
      self::generatePlayers($team);
      $team->updateValue();
    }
    return response()->json(['message' => 'Email has been successfully verified'], 200);
  }
  public static function generatePlayers($team)
  {
    foreach (Controller::PLAYER_LIST as $role => $count) {
      while ($count) {
        $player = Player::generate($role);
        $player->team()->associate($team)->save();
        $count--;
      }
    }
  }
  public function resend(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);
    if ($user = User::where('email', $request->email)->first()) {
      $user->sendEmailVerificationNotification();
    }
    return response()->json(['message' => 'Verification has been sent'], 200);
  }
  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);
    $token = auth()->attempt(['email' => $request->email, 'password' => $request->password]);
    if ($token == false || !auth()->user()->hasVerifiedEmail()) {
      return response()->json(['message' => 'Wrong credentials'], 400);
    }
    return response()->json([
      'message' => 'Successfully logged in',
      'token' => $token,
    ]);
  }
}
