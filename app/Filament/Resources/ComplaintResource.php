<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Filament\Resources\ComplaintResource\RelationManagers;
use App\Filament\Resources\ComplaintResource\RelationManagers\ComplaintRepliesRelationManager;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('category_id')
                        ->relationship(name: 'category', titleAttribute: 'title')
                        ->required(),
                    Forms\Components\Textarea::make('content')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('photo')
                        ->directory('complaints')
                        ->required()
                        ->image()
                        ->imageEditor()
                        ->imageCropAspectRatio('1:1')
                        ->imageEditorAspectRatios(['1:1']),
                    Forms\Components\Select::make('status')
                        ->options([
                            'waiting' => 'Waiting',
                            'replied' => 'Replied',
                            'done' => 'Done',
                        ])
                        ->default('waiting')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')->rowIndex(),
                Tables\Columns\TextColumn::make('category.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->height(100)
                    ->square(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'waiting' => 'warning',
                        'replied' => 'info',
                        'done' => 'success',
                    }),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()->schema([
                    TextEntry::make('category.title'),
                    TextEntry::make('title'),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'waiting' => 'warning',
                            'replied' => 'info',
                            'done' => 'success',
                        }),
                    TextEntry::make('createdBy.name'),
                    TextEntry::make('updatedBy.name'),
                    ImageEntry::make('photo')
                        ->height(100)
                        ->square(),
                ])->columns(3),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            ComplaintRepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'view' => Pages\ViewComplaint::route('/{record}'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }
}
