<?php

namespace App\Filament\Resources\MessageResource\Pages;

use Filament\Actions;
use App\Traits\PushNotification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MessageResource;
use App\Models\UserMessage;

class CreateMessage extends CreateRecord
{
    use PushNotification;

    protected static string $resource = MessageResource::class;

    protected function afterCreate(): void
    {
        $storedDataId = $this->record->getKey();
        $users = UserMessage::where('message_id', $storedDataId)->get();
        $deviceTokens = [];
        foreach ($users as $user) {
            if($user->device_token != null){
                $deviceTokens[] = $user->device_token;
            }
        }
        if ($deviceTokens) {
            $this->sendPushNotificationMultiple(
                $deviceTokens,
                $this->record->title,
                $storedDataId,
                ['id' => $storedDataId],
            );
        } else {
            $this->sendPushNotificationTopic(
                'global_message',
                $this->record->title,
                $storedDataId,
                ['id' => $storedDataId],
            );
        }
    }
}
