<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers\UnitsRelationManager;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TenantResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('avatar')
                        ->label('Avatar')
                        ->directory('avatars')
                        ->image()
                        ->imageEditor()
                        ->imageCropAspectRatio('1:1')
                        ->imageEditorAspectRatios(['1:1']),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('birthdate')
                        ->native(false)
                        ->maxDate(now()),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->required()
                        ->maxLength(255)
                        ->hiddenOn(['view', 'edit']),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('role', 'tenant');
            })
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->circular(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified'),
                Tables\Columns\IconColumn::make('is_blocked')
                    ->boolean()
                    ->label('Blocked'),
                Tables\Columns\TextColumn::make('units_count')
                    ->counts('units')
                    ->label('Total Unit'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
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
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()->schema([
                    TextEntry::make('name'),
                    TextEntry::make('email'),
                    TextEntry::make('birthdate')
                        ->placeholder('-')
                        ->dateTime(),
                    TextEntry::make('verified_at')
                        ->dateTime()
                        ->placeholder('-'),
                    TextEntry::make('adminVerified.name')
                        ->label('Verified By')
                        ->placeholder('-'),
                    TextEntry::make(''),
                    TextEntry::make('blocked_at')
                        ->placeholder('-')
                        ->dateTime(),
                    TextEntry::make('adminBlock.name')
                        ->label('Blocked By')
                        ->placeholder('-'),
                    TextEntry::make('block_message')
                        ->placeholder('-'),
                ])->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UnitsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'view' => Pages\ViewTenant::route('/{record}'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
