<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

trait PushNotification
{
    public function sendPushNotification($deviceToken, $title, $message, $data = null)
    {
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');

        $messaging = $firebase->createMessaging();

        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $message,
            ],
            'data' => $data,
        ]);

        $messaging->send($message);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationMultiple($deviceTokens, $title, $message, $data = null)
    {
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');

        $messaging = $firebase->createMessaging();

        $messages = [];
        foreach ($deviceTokens as $deviceToken) {
            $messages[] = CloudMessage::fromArray([
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'data' => $data,
            ]);
        }

        $messaging->sendAll($messages);

        return response()->json(['message' => 'Push notification sent successfully']);
    }

    public function sendPushNotificationTopic($topic, $title, $message, $data = null)
    {
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');

        $messaging = $firebase->createMessaging();

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => [
                'title' => $title,
                'body' => $message,
            ],
            'data' => $data,
        ]);

        $messaging->send($message);

        return response()->json(['message' => 'Push notification sent successfully']);
    }
}
