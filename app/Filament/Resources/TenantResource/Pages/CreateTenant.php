<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        $data['verified_at'] = now();
        $data['verified_by'] = auth()->user()->id;
        $data['role'] = 'tenant';
        $record =  static::getModel()::create($data);

        return $record;
    }
}
