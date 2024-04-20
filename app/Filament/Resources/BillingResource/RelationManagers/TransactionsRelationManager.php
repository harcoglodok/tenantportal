<?php

namespace App\Filament\Resources\BillingResource\RelationManagers;

use App\Models\Billing;
use App\Models\BillingTransaction;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->date(),
                Tables\Columns\ImageColumn::make('image')
                    ->width(150)
                    ->height(200),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'declined' => 'danger',
                }),
                Tables\Columns\TextColumn::make('message')->placeholder('-'),
                Tables\Columns\TextColumn::make('verifiedBy.name')->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('approveTransaction')
                    ->label('Approve')
                    ->color('success')
                    ->disabled(fn (BillingTransaction $record): bool => $record->status != 'pending')
                    ->icon('heroicon-m-check-badge')
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('message'),
                    ])
                    ->action(function (BillingTransaction $record, array $data) {
                        $record->update([
                            'verified_by' => auth()->user()->id,
                            'status' => 'approved',
                            'message' => $data['message'] ?? '',
                        ]);
                        try {
                            $billing = Billing::find($record->billing_id);
                            $billing->update([
                                'status' => 'paid',
                            ]);
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        Notification::make()
                            ->success()
                            ->title('Approved')
                            ->body('Transaction approved successfully.')
                            ->send();
                    }),
                Action::make('declineTransaction')
                    ->label('Decline')
                    ->color('danger')
                    ->icon('heroicon-m-no-symbol')
                    ->disabled(fn (BillingTransaction $record): bool => $record->status != 'pending')
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('message')
                            ->required(),
                    ])
                    ->action(function (BillingTransaction $record, array $data) {
                        $record->update([
                            'verified_by' => auth()->user()->id,
                            'status' => 'declined',
                            'message' => $data['message'],
                        ]);
                        Notification::make()
                            ->success()
                            ->title('Declined')
                            ->body('Transaction declined successfully.')
                            ->send();
                    })
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
