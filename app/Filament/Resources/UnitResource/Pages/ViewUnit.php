<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUnit extends ViewRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('owner')
                ->label('Owner')
                ->url(route('filament.admin.resources.tenants.view', ['record' => $this->getRecord()->user_id]))
                ->color('info'),
            Actions\EditAction::make(),
        ];
    }
}
