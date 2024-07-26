<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request) {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'user' => $user
            ], 200);
        }
    }
    public function login(Request $request) {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication fails',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Logged in successfully',
                'token' => $authUser->createToken('API Token')->plainTextToken, //predefined key
                'token_type' => 'bearer'    //predefined key
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not matched.',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }    
    }  
    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete(); //delete all related tokens
        //$user->tokens('API KEY')->delete(); //delete specific token on user
        return response()->json([
            'status' => true,
            'user' => $user,    //optional
            'message' => 'You Logged out successfully'
        ], 200);
    }
}
