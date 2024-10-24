<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;


trait PushNotification
{
    public function sendPushNotification($deviceToken, $title, $body, $data = null)
    {
        $factory = (new Factory)->withServiceAccount(storage_path('firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);

        $result = $messaging->send($message);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationMultiple($deviceTokens, $title, $body, $data = null)
    {
        $factory = (new Factory)->withServiceAccount(storage_path('firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::fromArray([
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);

        $result = $messaging->sendMulticast($message, $deviceTokens);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationTopic($topic, $title, $body, $data = null)
    {
        $factory = (new Factory)->withServiceAccount(storage_path('firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);

        $result = $messaging->send($message);

        return response()->json(['message' => 'Push notification sent successfully']);
    }
}
