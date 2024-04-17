<?php

namespace App\Filament\Resources\BillingImportLogResource\Pages;

use App\Filament\Resources\BillingImportLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillingImportLog extends EditRecord
{
    protected static string $resource = BillingImportLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
