<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;

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
            } else if ($user->blocked_at != null) {
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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'birthdate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorWithData('Gagal melakukan pendaftaran', $validator->errors()->toArray(), 422);
        }

        $userData = $request->only(['name', 'email', 'password', 'avatar', 'birthdate']);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = $this->fileUpload('avatars', $avatar);
            $userData['avatar'] = $avatarPath;
        }

        $userData['password'] = bcrypt($userData['password']);
        $userData['role'] = 'tenant';

        $user = User::create($userData);

        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('Terdapat user baru yang melakukan registrasi pada aplikasi')
                ->sendToDatabase($admins);
        }

        return $this->sendResponse(new UserResource($user), 'Registrasi berhasil', 201);
    }

    public function forgotPassword(Request $request)
    {
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = app('auth.password.broker')->createToken($user);
                $notification = new \Filament\Notifications\Auth\ResetPassword($token);
                $notification->url = \Filament\Facades\Filament::getResetPasswordUrl($token, $user);
                $user->notify($notification);

                $admins = User::whereIn('role', ['root', 'admin'])->get();
                if ($admins) {
                    Notification::make()
                        ->title('Email ' . $request->email . ' melakukan request perubahan password!')
                        ->sendToDatabase($admins);
                }

                return $this->sendSuccess('Reset password berhasil dikirimkan ke email Anda!');
            }
        } else {
            return $this->sendError('Silahkan masukkan email Anda!');
        }

        return $this->sendError('User tidak dtemukan!');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(null, 'Logout Berhasil');
    }
}
