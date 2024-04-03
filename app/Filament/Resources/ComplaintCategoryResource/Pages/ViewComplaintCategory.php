<?php

namespace App\Filament\Resources\ComplaintCategoryResource\Pages;

use App\Filament\Resources\ComplaintCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaintCategory extends ViewRecord
{
    protected static string $resource = ComplaintCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
