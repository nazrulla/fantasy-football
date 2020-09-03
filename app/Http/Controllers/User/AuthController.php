<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required|confirmed|min:6',
      'email' => 'required|email',
    ]);
    $user = User::create($request->except('password_confirmation'))->sendEmailVerificationNotification();
    return response()->json(['message' => __('auth.success')], 200);
  }
  public function verify(Request $request, $user_id){
    if(!$request->hasValidSignature()){
      return response()->json(['message' => 'Invalid/Expired url provided.'], 401);
    }
    $user = User::findOrFail($user_id);

    if (!$user->hasVerifiedEmail()){
      $user->markEmailAsVerified();
      
    }
    return response()->json(['message' => 'Email has been verified'], 200);
  }
  public function resend(Request $request){
    $request->validate([
      'email' => 'required|email'
    ]);
    if($user = User::where('email', $request->email)->first()){
      $user->sendEmailVerificationNotification();
    }
    return response()->json(['message' => 'Verification has been sent'], 200);
  }
}
