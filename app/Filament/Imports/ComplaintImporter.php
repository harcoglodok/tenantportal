<?php

namespace App\Filament\Imports;

use App\Models\Unit;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;

class ComplaintImporter extends Importer
{
    protected static ?string $model = Complaint::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?Complaint
    {
        try {
            $category = ComplaintCategory::find($this->data['complaint_type_id']);
            $unit = Unit::find($this->data['created_by']);
            $photo = '';
            if ($this->data['photo']) {
                $photo = 'complaints/' . $this->data['photo'];
            }
            return Complaint::firstOrNew([
                'category_id' => $category->id,
                'title' => 'Komplain ' . $category->title,
                'content' => $this->data['content'],
                'photo' => $photo,
                'status' => 'done',
                'unit_id' => optional($unit)->id,
                'created_by' => optional($unit)->user_id ?? 1,
                'updated_by' => optional($unit)->user_id ?? 1,
                "created_at" => $this->data["created_at"],
                "updated_at" => $this->data["updated_at"],
            ]);
        } catch (\Throwable $th) {
            throw new RowImportFailedException("Error [{$th->getMessage()}].");
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your complaint import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
