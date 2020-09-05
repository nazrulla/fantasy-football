<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);
    $token = auth('admin')->attempt([
      'login' => $request->username,
      'password' => $request->password
    ]);
    if ($token == false) {
      return response()->json([
        'message' => 'Wrong credentials'
      ], 400);
    }
    return response()->json([
      'message' => 'Successfully logged in',
      'token' => $token,
    ]);
  }
}
