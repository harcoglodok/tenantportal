<?php

namespace App\Filament\Resources\MessageResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\MessageImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MessageResource;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('importMessages')
            //     ->label('Import Messages')
            //     ->color('success')
            //     ->icon('heroicon-m-arrow-up-tray')
            //     ->form([
            //         FileUpload::make('import')
            //             ->directory('imports')
            //             ->label('Import File'),
            //     ])
            //     ->action(function (array $data) {
            //         $file = public_path("storage/" . $data['import']);

            //         Excel::import(new MessageImport, $file);

            //         Notification::make()
            //             ->success()
            //             ->title('Messages Imported')
            //             ->body('Successfully import messages')
            //             ->send();
            //     }),
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();
                    $data['updated_by'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
