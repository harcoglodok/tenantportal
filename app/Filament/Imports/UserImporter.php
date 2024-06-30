<?php

namespace App\Filament\Imports;

use App\Models\Unit;
use App\Models\User;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?User
    {
        try {
            $user = User::whereEmail($this->data["email"])->first();
            if (!$user) {
                $user = User::create([
                    "name" => $this->data["email"],
                    "email" => $this->data["email"],
                    "password" => $this->data["password_hash"],
                    "religion" => $this->data["religion"] != 'NULL' ? $this->data["religion"] : null,
                    "birthdate" => $this->data["birthday"] != '0000-00-00' ? $this->data["birthday"] : null,
                    "device_token" => $this->data["device_token"] != 'NULL' ? $this->data["device_token"] : null,
                    "verified_at" => now(),
                    "verified_by" => 1,
                    "role" => "tenant",
                    "created_at" => $this->data["created_at"],
                    "updated_at" => $this->data["updated_at"],
                ]);
            }
            Unit::create([
                "id" => $this->data["id"],
                "no_unit" => $this->data["username"],
                "user_id" => $user->id,
                "created_at" => $this->data["created_at"],
                "updated_at" => $this->data["updated_at"],
            ]);
            return $user;
        } catch (\Throwable $th) {
            throw new RowImportFailedException("Error [{$th->getMessage()}].");
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
