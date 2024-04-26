<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CronAPIController extends AppBaseController
{
    public function scheduledNotification(Request $request)
    {
        // TODO: Cron Scheduled Notification
        return $this->sendResponse(null, 'Scheduled Notification');
    }

    public function birthdayNotification(Request $request)
    {
        // TODO: Cron Birthday Notification
        return $this->sendResponse(null, 'Bithday Notification');
    }

    public function doneComplaint(Request $request)
    {
        // TODO: Cron Birthday Notification
        return $this->sendResponse(null, 'Complaint Done');
    }
}
