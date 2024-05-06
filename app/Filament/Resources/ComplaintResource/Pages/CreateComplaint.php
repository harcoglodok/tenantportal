<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use App\Models\Complaint;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateComplaint extends CreateRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function handleRecordCreation(array $data): Complaint
    {
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        $record =  static::getModel()::create($data);

        return $record;
    }
}
