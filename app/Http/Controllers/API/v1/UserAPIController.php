<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AppBaseController;

class UserAPIController extends AppBaseController
{
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Failed get data');
        }

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        $user = User::find($user->id);

        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('User ' . $user->name . ' melakukan update password')
                ->sendToDatabase($admins);
        }

        return $this->sendResponse(new UserResource($user), 'Update password successfully');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($request->email) {
            $checkUserEmail = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
            if($checkUserEmail){
                return $this->sendError('Email sudah digunakan');
            }
        }
        $data['name'] = $request->name ?? $user->name;
        $data['email'] = $request->email ?? $user->email;
        $data['birthdate'] = $request->birthdate ?? $user->birthdate;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $data['avatar'] = $this->fileUpdate('avatars', $file, $user->avatar);
        }
        $user->update($data);
        $user = User::find($user->id);

        $admins = User::whereIn('role', ['root', 'admin'])->get();
        if ($admins) {
            Notification::make()
                ->title('User ' . $user->name . ' melakukan update data')
                ->sendToDatabase($admins);
        }
        return $this->sendResponse(new UserResource($user), 'Update user successfully');
    }
}
