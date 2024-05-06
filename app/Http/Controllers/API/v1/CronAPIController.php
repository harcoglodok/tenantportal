<?php

namespace App\Http\Controllers\API\v1;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ScheduledNotification;
use App\Http\Controllers\AppBaseController;

class CronAPIController extends AppBaseController
{
    public function scheduledNotification(Request $request)
    {
        $today = Carbon::today();
        $notifications = ScheduledNotification::whereDate('date', $today)->get();
        foreach ($notifications as $notification) {
            $this->sendPushNotificationTopic(
                'general',
                $notification->title,
                $notification->message,
            );
        }
        return $this->sendResponse(null, 'Scheduled Notification');
    }

    public function birthdayNotification(Request $request)
    {
        $today = Carbon::today();
        $users = User::whereMonth('birthdate', $today->month)
            ->whereDay('birthdate', $today->day)
            ->get();
        foreach ($users as $user) {
            if ($user->device_token != null) {
                $this->sendPushNotification(
                    $user->device_token,
                    $user->name . ' Ulang Tahun Hari Ini',
                    'Selamat Ulang Tahun. Semoga sukses & bahagia',
                );
            }
        }
        return $this->sendResponse(null, 'Bithday Notification');
    }

    public function doneComplaint(Request $request)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $oldComplaint = Complaint::where('updated_at', '<', $sevenDaysAgo)->where('status', '!=', 'done')->get();
        foreach ($oldComplaint as $complaint) {
            $complaint->update(['status' => 'done', 'updated_at' => now()]);
            $user = $complaint->createdBy;
            if ($user->device_token != null) {
                $this->sendPushNotification(
                    $user->device_token,
                    'Saran/Kritik Anda diselesaikan oleh system karena tidak ada aktifitas',
                    '',
                );
            }
        }
        return $this->sendResponse(null, 'Complaint Done');
    }
}
