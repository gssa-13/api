<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]);
        $user = User::whereEmail($request->email)->first();

        if (! $user || Hash::check($request->email, $user->password))
        {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')]
            ]);
        }

        // generate token
        $plainTextToken = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'plain-text-token' => $plainTextToken
        ]);
    }
}
