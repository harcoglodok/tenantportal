<?php

namespace App\Filament\Imports;

use App\Models\Unit;
use App\Models\Message;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;

class MessageImporter extends Importer
{
    protected static ?string $model = Message::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?Message
    {
        try {
            if ($this->data['tenant_store'] == "*") {
                $unit = Unit::find($this->data['created_by']);
                return Message::firstOrNew([
                    'title' => $this->data['title'],
                    'photo' => '',
                    'content' => $this->data['message'],
                    'created_by' => optional($unit)->user_id ?? 1,
                    'updated_by' => optional($unit)->user_id ?? 1,
                    "created_at" => $this->data["created_at"],
                    "updated_at" => $this->data["updated_at"],
                ]);
            }
            return null;
        } catch (\Throwable $th) {
            throw new RowImportFailedException("Error [{$th->getMessage()}].");
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your message import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
