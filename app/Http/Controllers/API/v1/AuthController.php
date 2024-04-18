<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends AppBaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->sendError('Failed Login');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            $message = 'Login Success!!';

            return $this->sendResponse(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
                $message,
            );
        }

        return $this->sendError('Sign In Failed', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success('Logout Berhasil');
    }
}
