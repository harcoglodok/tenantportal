<?php

namespace App\Filament\Imports;

use App\Models\Billing;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class OldBillingImporter extends Importer
{
    protected static ?string $model = Billing::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?Billing
    {
        ini_set('max_execution_time', '300');
        $billing = Billing::firstOrNew([
            'inv_no' => $this->data['code'],
            'month' => $this->data['month'],
            'year' => $this->data['year'],
            'unit_no' => $this->data['unit_number'],
            's4_mbase_amt' => doubleval($this->data['s4_mbase_amt']),
            's4_mtax_amt' => doubleval($this->data['s4_mtax_amt']),
            'sd_mbase_amt' => doubleval($this->data['sd_mbase_amt']),
            'service_charge' => doubleval($this->data['service_charge']),
            'sinking_fund' => doubleval($this->data['sinking_fund']),
            'electric_previous' => doubleval($this->data['electric_previous']),
            'electric_current' => doubleval($this->data['electric_current']),
            'electric_read' => doubleval($this->data['electric_read']),
            'electric_fixed' => doubleval($this->data['electric_fixed']),
            'electric_administration' => doubleval($this->data['electric_administration']),
            'electric_tax' => doubleval($this->data['electric_tax']),
            'electric_total' => doubleval($this->data['electric_total']),
            'mcb' => $this->data['mcb'] ?? '',
            'water_previous' => doubleval($this->data['water_previous']),
            'water_current' => doubleval($this->data['water_current']),
            'water_read' => doubleval($this->data['water_read']),
            'water_fixed' => doubleval($this->data['water_fixed']),
            'water_mbase' => doubleval($this->data['water_mbase']),
            'water_administration' => doubleval($this->data['water_administration']),
            'water_tax' => doubleval($this->data['water_tax']),
            'water_total' => doubleval($this->data['water_total']),
            'total' => doubleval($this->data['total']),
            'tube' => $this->data['tube'] ?? '',
            'panin' => $this->data['panin'] ?? '',
            'bca' => $this->data['bca'] ?? '',
            'cimb' => $this->data['cimb'] ?? '',
            'mandiri' => $this->data['mandiri'] ?? '',
            'add_charge' => doubleval($this->data['add_charge']),
            'previous_transaction' => doubleval($this->data['previous_transaction']),
            'status' => 'paid',
            'created_at' => $this->data['created_at'],
            'updated_at' => $this->data['updated_at'],
        ]);

        return $billing;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your old billing import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
