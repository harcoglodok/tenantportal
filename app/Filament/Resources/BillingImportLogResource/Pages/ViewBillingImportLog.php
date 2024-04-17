<?php

namespace App\Filament\Resources\BillingImportLogResource\Pages;

use App\Filament\Resources\BillingImportLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBillingImportLog extends ViewRecord
{
    protected static string $resource = BillingImportLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
