<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['verified_at'] = Carbon::now();
                    $data['verified_by'] = auth()->user()->id;
                    $data['role'] = 'admin';
                    $data['password'] = bcrypt($data['password']);

                    return $data;
                }),
        ];
    }
}
