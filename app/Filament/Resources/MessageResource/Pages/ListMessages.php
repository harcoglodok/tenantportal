<?php

namespace App\Filament\Resources\MessageResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Imports\MessageImport;
use Filament\Actions\ImportAction;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use App\Filament\Imports\MessageImporter;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MessageResource;

class ListMessages extends ListRecords
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ImportAction::make()->importer(MessageImporter::class),
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->id();
                    $data['updated_by'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
