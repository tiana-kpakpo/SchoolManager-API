<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login (Request $request)
    {
        $input = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $input['email'])->first();

        if(!Hash::check($input['password'], $user->password)){
            return response(['Message'=>'wrong credentials'], 401);
        }

        $token = $user -> createToken('AuthToken') -> plainTextToken;

        return response()->json([
            'user' => $user, 
            'token' => $token,
            'message' => 'Login successful'], 200);

    }
    

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Old password is incorrect'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function logout () {
        
    }

}
