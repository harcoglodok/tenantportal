<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\ComplaintImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ComplaintResource;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('importComplaints')
            //     ->label('Import Complaints')
            //     ->color('success')
            //     ->icon('heroicon-m-arrow-up-tray')
            //     ->form([
            //         FileUpload::make('import')
            //             ->directory('imports')
            //             ->label('Import File'),
            //     ])
            //     ->action(function (array $data) {
            //         $file = public_path("storage/" . $data['import']);

            //         Excel::import(new ComplaintImport, $file);

            //         Notification::make()
            //             ->success()
            //             ->title('Complaints Imported')
            //             ->body('Successfully import complaints')
            //             ->send();
            //     }),
            // Actions\CreateAction::make(),
        ];
    }
}
