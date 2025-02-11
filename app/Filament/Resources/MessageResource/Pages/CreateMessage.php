<?php

namespace App\Filament\Resources\MessageResource\Pages;

use Filament\Actions;
use App\Models\Message;
use App\Models\UserMessage;
use App\Traits\PushNotification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MessageResource;

class CreateMessage extends CreateRecord
{
    use PushNotification;

    protected static string $resource = MessageResource::class;

    protected function handleRecordCreation(array $data): Message
    {
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $record =  static::getModel()::create($data);

        return $record;
    }

    protected function afterCreate(): void
    {
        $storedDataId = $this->record->getKey();
        $users = UserMessage::where('message_id', $storedDataId)->get();
        $deviceTokens = [];
        foreach ($users as $user) {
            if ($user->user->device_token != null) {
                $deviceTokens[] = $user->user->device_token;
            }
        }
        if (!$users->isEmpty()) {
            if ($deviceTokens) {
                $this->sendPushNotificationMultiple(
                    $deviceTokens,
                    $this->record->title,
                    '',
                    ['id' => $storedDataId],
                );
            }
        } else {
            $this->sendPushNotificationTopic(
                'global_message',
                $this->record->title,
                '',
                ['id' => $storedDataId],
            );
        }
    }
}
