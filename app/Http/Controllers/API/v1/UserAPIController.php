<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'password' => Hash::make($request->new_password),
        ]);

        $user = User::find($user->id);

        return $this->sendResponse(new UserResource($user), 'Update password successfully');
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $data['name'] = $request->name ?? $user->name;
        $data['email'] = $request->email ?? $user->email;
        $user->update($data);
        $user = User::find($user->id);
        return $this->sendResponse(new UserResource($user), 'Update user successfully');
    }
}
