<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\StoreNewUser;
use App\Http\Requests\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationEmail;

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
        $user->activation_token = $this->generateActivationtoken();
        $user->activated = 0;
        $user->save();
        if (!$user) {
            return response()->json([
                'status' => 'failed to create user', 
            ]);
        }

        Mail::to($user->email)->send(new ActivationEmail($user));

        return response()->json([
            'status' => 'success', 
        ]);
    }

    public function activation (Request $request)
    {
        $activation_token = $request->val;
        $user = User::where('activation_token', $activation_token)->first();

        if ($user) {
            $user->email_verified_at = time();
            $user->activated = 1;
            $user->save();
        }

        return view('activation');
    }

    /**
     * Logs the users in
     */
    public function login(Login $request)
    {
        $credentials = $request->only('email', 'password');

        $email = $credentials['email'];
        $user = User::where('email', $email)->first();

        if (!$user->activated) {
            return response()->json([
                'status' => 'Account not activated', 
            ]);
        }

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

    public function generateActivationtoken($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
