<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\ComplaintImport;
use Filament\Actions\ImportAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\ComplaintImporter;
use App\Filament\Resources\ComplaintResource;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ImportAction::make()->importer(ComplaintImporter::class),
        ];
    }
}
