<?php

namespace App\Filament\Resources\TenantResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use App\Filament\Imports\UserImporter;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TenantResource;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()->importer(UserImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
