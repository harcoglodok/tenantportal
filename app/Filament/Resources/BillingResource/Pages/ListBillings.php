<?php

namespace App\Filament\Resources\BillingResource\Pages;

use App\Filament\Resources\BillingResource;
use App\Imports\BillingsImport;
use App\Models\BillingImportLog;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListBillings extends ListRecords
{
    protected static string $resource = BillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importBillings')
                ->label('Import Billings')
                ->color('success')
                ->icon('heroicon-m-arrow-up-tray')
                ->form([
                    FileUpload::make('import')
                        ->directory('billings')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->label('Import File Excel'),
                ])
                ->action(function (array $data) {
                    // $data is an array which consists of all the form data
                    $file = public_path("storage/billings/" . $data['import']);

                    BillingImportLog::create([
                        'user_id' => auth()->user()->id,
                        'file' => 'billings/'.$data['import']
                    ]);

                    Excel::import(new BillingsImport, $file);

                    Notification::make()
                        ->success()
                        ->title('Billings Imported')
                        ->body('Billings data imported successfully.'.$file)
                        ->send();
                }),
            // Actions\CreateAction::make(),
        ];
    }
}
