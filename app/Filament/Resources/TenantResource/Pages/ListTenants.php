<?php

namespace App\Filament\Resources\TenantResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\TenantImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TenantResource;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('importUsers')
            //     ->label('Import Users')
            //     ->color('success')
            //     ->icon('heroicon-m-arrow-up-tray')
            //     ->form([
            //         FileUpload::make('import')
            //             ->directory('imports')
            //             ->label('Import File'),
            //     ])
            //     ->action(function (array $data) {
            //         $file = public_path("storage/" . $data['import']);

            //         Excel::import(new TenantImport, $file);

            //         Notification::make()
            //             ->success()
            //             ->title('Users Imported')
            //             ->body('Successfully import users')
            //             ->send();
            //     }),
            Actions\CreateAction::make(),
        ];
    }
}
