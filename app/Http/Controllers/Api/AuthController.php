<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'username'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->generateResult(
                $validator->errors(),
                2,
                400,
            );
        }
        $api_token = Str::random(80);
        $user = User::create([
            'username'  => $request->json('username'),
            'email'     => $request->json('email'),
            'password'  => Hash::make($request->json('password')),
            'api_token' => $api_token,
        ]);

        return $this->generateResult(
            [
                'message'   => 'Hi ' . $user->username . ', Welcome!',
                'user' => $user
            ],
            1,
            200,
            ['access_token' => $api_token]
        );
    }

    public function login(Request $request)
    {
        if (!Auth::attempt([
            'email' => $request->json('email'),
            'password' => $request->json('password')
        ]))
            return $this->generateResult(
                ['message' => 'Unauthorized'],
                2,
                401
            );

        $api_token = Str::random(80);
        $user = User::where('email', $request->json('email'))->firstOrFail();
        $user->forceFill([
            'api_token' => hash('sha256', $api_token),
        ])->save();
        return $this->generateResult(
            [
                'message'   => 'Hi ' . $user->username . ', Welcome!',
                'user'      => $user
            ],
            1,
            200,
            ['access_token' => $api_token]
        );
    }

    public function profile()
    {
        return $this->generateResult(
            ['user' => auth()->user()],
            1,
            200
        );
    }

    public function logout()
    {
        User::find(auth()->id())->forceFill([
            'api_token' => null,
        ])->save();
        return $this->generateResult(
            [
                'message' => 'You have successfully logged out and the token was successfully deleted',
                'user'    => null,
            ],
            1,
            200
        );
    }

    public function deleteAccount()
    {
        User::find(auth()->id())->delete();
        return $this->generateResult(
            [
                'message' => 'Account successfully deleted',
                'user'    => null
            ],
            1,
            200
        );
    }

    public function unauthorized()
    {
        return $this->generateResult(
            [
                'message' => 'Unauthorized',
            ],
            2,
            401
        );
    }
}
