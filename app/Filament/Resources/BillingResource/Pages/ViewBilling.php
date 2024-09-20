<?php

namespace App\Filament\Resources\BillingResource\Pages;

use Filament\Actions;
use Livewire\Attributes\On;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\BillingResource;

class ViewBilling extends ViewRecord
{
    protected static string $resource = BillingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    #[On('refreshData')]
    public function refresh(): void {}
}
