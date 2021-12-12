<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
   
        return response()->json([
            'token' => $token
        ], 201);
    }
}
