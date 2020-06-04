<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\StoreNewUser;
use App\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Creates a new user
     */
    public function create(StoreNewUser $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        if (!$user) {
            return response()->json([
                'status' => 'failed to create user', 
            ]);
        }

        return response()->json([
            'status' => 'success', 
        ]);
    }

    /**
     * Logs the users in
     */
    public function login(Login $request)
    {
        $credentials = $request->only('email', 'password');

        //return $credentials;

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error', 
            ]);
        }

        $token = Auth::user()->createToken('token')->accessToken;

        return response()->json([
            'user' => Auth::user(),
            'token' => $token,
        ]);
    }

    /**
     * Logs out user
     */
    public function logout() {
        if (Auth::check()) {
            Auth::user()->AauthAccessToken()->delete();
            return response()->json([
                'status' => 'Logged out', 
            ]);
        } else {
            return response()->json([
                'error' => 'Logout unsuccessful', 
            ]);
        }
    }

    /**
     * Test function for authenticated user
     */
    public function get() {
        return User::all();
    }

    /**
     * Resets the users password
     */
    public function passwordReset(Request $request) {
        //return $request['password'];
        if (!$request['password']) {
            return response()->json([
                'error' => 'No new password supplied', 
            ]);
        }

        $user = User::find(Auth::user()->id);
        if (!$user) {
            return response()->json([
                'error' => 'User not found', 
            ]);
        }
        $user->password = bcrypt($request->password);
        $user->save();

        if ($user) {
            return response()->json([
                'status' => 'Password Updated', 
            ]);
        }
    }
}
