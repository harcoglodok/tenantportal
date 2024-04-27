<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingImportLogResource\Pages;
use App\Filament\Resources\BillingImportLogResource\RelationManagers;
use App\Models\BillingImportLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingImportLogResource extends Resource
{
    protected static ?string $model = BillingImportLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('file')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('file'),
                Tables\Columns\TextColumn::make('successCount')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('failedCount')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Import at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //     Tables\Actions\ForceDeleteBulkAction::make(),
                //     Tables\Actions\RestoreBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc');
    }



    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()->schema([
                    TextEntry::make('user.name'),
                    TextEntry::make('created_at')->label('Import at'),
                    TextEntry::make('successCount')
                        ->badge()
                        ->color('success')
                        ->label('Total Success'),
                    TextEntry::make('failedCount')
                        ->badge()
                        ->color('danger')
                        ->label('Total Failed'),
                    TextEntry::make('billingImportLogDataSuccess')
                        ->listWithLineBreaks()
                        ->formatStateUsing(fn (string $state): string => json_decode($state, true)['message'])
                        ->badge()
                        ->color('success')
                        ->label('Success'),
                    TextEntry::make('billingImportLogDataFailed')
                        ->listWithLineBreaks()
                        ->formatStateUsing(fn (string $state): string => json_decode($state, true)['message'])
                        ->badge()
                        ->color('danger')
                        ->label('Failed'),
                ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBillingImportLogs::route('/'),
            // 'create' => Pages\CreateBillingImportLog::route('/create'),
            // 'view' => Pages\ViewBillingImportLog::route('/{record}'),
            // 'edit' => Pages\EditBillingImportLog::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
