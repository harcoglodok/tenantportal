<?php

namespace App\Filament\Resources\TenantResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TenantResource;

class ViewTenant extends ViewRecord
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verifyUser')
                ->label('Verify User')
                ->color('success')
                ->icon('heroicon-m-check-badge')
                ->disabled(fn (User $record) => $record->verified_at != null)
                ->requiresConfirmation()
                ->action(function (User $record) {
                    $record->update([
                        'verified_at' => Carbon::now(),
                        'verified_by' => auth()->user()->id,
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Verify Successed')
                        ->body('User verified successfully.')
                        ->send();
                }),
            Action::make('blockUser')
                ->label('Block User')
                ->color('danger')
                ->icon('heroicon-m-no-symbol')
                ->requiresConfirmation()
                ->disabled(fn (User $record) => $record->blocked_at != null)
                ->form([
                    TextInput::make('message')
                        ->required(),
                ])
                ->action(function (User $record, array $data) {
                    $record->update([
                        'blocked_at' => Carbon::now(),
                        'blocked_by' => auth()->user()->id,
                        'block_message' => $data['message'],
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Block Successed')
                        ->body('User blocked successfully.')
                        ->send();
                }),
            Action::make('unblockUser')
                ->label('Unblock User')
                ->color('info')
                ->icon('heroicon-m-lock-open')
                ->requiresConfirmation()
                ->disabled(fn (User $record) => $record->blocked_at == null)
                ->action(function (User $record) {
                    $record->update([
                        'blocked_at' => null,
                        'blocked_by' => null,
                        'block_message' => '',
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Unblock Successed')
                        ->body('User unblock successfully.')
                        ->send();
                }),
            Actions\EditAction::make()
                ->icon('heroicon-m-pencil'),
        ];
    }
}
