<?php

namespace App\Traits;


trait PushNotification
{
    public function sendPushNotification($deviceToken, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];
        $jsonData = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . env('PUSHNOTIFKEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($ch);
        curl_close($ch);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationMultiple($deviceTokens, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            'registration_ids' => $deviceTokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];
        $jsonData = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . env('PUSHNOTIFKEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($ch);
        curl_close($ch);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationTopic($topic, $title, $body, $data = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $data = [
            'to' => '/topics/' . $topic,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ];
        $jsonData = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . env('PUSHNOTIFKEY'),
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($ch);
        curl_close($ch);

        return response()->json(['message' => 'Push notification sent successfully']);
    }
}
