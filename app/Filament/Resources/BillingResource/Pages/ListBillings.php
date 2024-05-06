<?php

namespace App\Filament\Resources\BillingResource\Pages;

use Filament\Actions\Action;
use App\Imports\BillingsImport;
use App\Models\BillingImportLog;
use App\Imports\OldBillingImport;
use App\Models\BillingImportLogData;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BillingResource;

class ListBillings extends ListRecords
{
    protected static string $resource = BillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('importBillingsOld')
            //     ->label('Import Billings Old')
            //     ->color('success')
            //     ->icon('heroicon-m-arrow-up-tray')
            //     ->form([
            //         FileUpload::make('import')
            //             ->directory('imports')
            //             ->label('Import File'),
            //     ])
            //     ->action(function (array $data) {
            //         $file = public_path("storage/" . $data['import']);

            //         Excel::import(new OldBillingImport, $file);

            //         Notification::make()
            //             ->success()
            //             ->title('Billings Imported')
            //             ->body('Successfully import billings')
            //             ->send();
            //     }),
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
                    $file = public_path("storage/" . $data['import']);

                    $log = BillingImportLog::create([
                        'user_id' => auth()->user()->id,
                        'file' => $data['import']
                    ]);

                    Excel::import(new BillingsImport, $file);

                    $successData = BillingImportLogData::where('billing_import_log_id', $log->id)
                        ->where('status', 'success')
                        ->count();
                    $failedData = BillingImportLogData::where('billing_import_log_id', $log->id)
                        ->where('status', 'failed')
                        ->count();

                    Notification::make()
                        ->success()
                        ->title('Billings Imported')
                        ->body('Successfully import ' . $successData . ' Invoice' . ($failedData ? (' & Failed to Import ' . $failedData . ' Invoice') : ''))
                        ->send();
                }),
            Action::make('importLogs')
                ->label('Import Logs')
                ->color('info')
                ->icon('heroicon-m-information-circle')
                ->url(route('filament.admin.resources.billing-import-logs.index')),
        ];
    }
}
