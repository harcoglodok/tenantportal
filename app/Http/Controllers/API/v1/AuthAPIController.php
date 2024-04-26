<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthAPIController extends AppBaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Failed Login');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            /** @var User $user */
            $user = Auth::user();
            if ($user->verified_at != null) {
                $token = $user->createToken('api-token')->plainTextToken;
                $message = 'Login Success!!';

                return $this->sendResponse(
                    [
                        'user' => new UserResource($user),
                        'token' => $token,
                    ],
                    $message,
                );
            } else if($user->blocked_at != null) {
                return $this->sendResponse(
                    [
                        'user' => new UserResource($user),
                        'token' => '-',
                    ],
                    'Akun user diblokir',
                );
            } else {
                return $this->sendResponse(
                    [
                        'user' => new UserResource($user),
                        'token' => '-',
                    ],
                    'User belum terverifikasi',
                );
            }
        }

        return $this->sendError('Sign In Failed', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(null, 'Logout Berhasil');
    }
}
