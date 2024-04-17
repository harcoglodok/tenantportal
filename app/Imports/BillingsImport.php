<?php

namespace App\Imports;

use App\Models\Billing;
use App\Models\BillingImportLog;
use App\Models\BillingImportLogData;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;

class BillingsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $log = BillingImportLog::latest()->take(1)->first();
        foreach ($rows as $index => $row) {
            if ($index == 0) {
                $pastData = Billing::where("month", $row['fin_month'])
                    ->where("year", $row['fin_year'])
                    ->get();
                if ($pastData->count() > 0) {
                    $pastData->delete();
                }
            }
            if ($this->validateRow($row)) {
                BillingImportLogData::create([
                    'billing_import_log_id' => $log->id,
                    'status' => 'success',
                    'message' => 'Successfully Import ' . trim($row['doc_no']) . ' to ' . $row['no_unit'],
                ]);
                // Billing::create([
                //     'inv_no' => $row['doc_no'],
                //     'month' => $row['fin_month'],
                //     'year' => $row['fin_year'],
                //     'tenant_id' => $row['tenant_id'],
                //     's4_mbase_amt' => $row['s4_mbase_amt'],
                //     's4_mtax_amt' => $row['s4_mtax_amt'],
                //     'sd_mbase_amt' => $row['sd_mbase_amt'],
                //     'service_charge' => $row['service_charge'],
                //     'sinking_fund' => $row['sinking_fund'],
                //     'electric_previous' => $row['electric_previous'],
                //     'electric_current' => $row['electric_current'],
                //     'electric_read' => $row['electric_read'],
                //     'electric_fixed' => $row['electric_fixed'],
                //     'electric_administration' => $row['electric_administration'],
                //     'electric_tax' => $row['electric_tax'],
                //     'electric_total' => $row['electric_total'],
                //     'mcb' => $row['mcb'],
                //     'water_previous' => $row['water_previous'],
                //     'water_current' => $row['water_current'],
                //     'water_read' => $row['water_read'],
                //     'water_fixed' => $row['water_fixed'],
                //     'water_mbase' => $row['water_mbase'],
                //     'water_administration' => $row['water_administration'],
                //     'water_tax' => $row['water_tax'],
                //     'water_total' => $row['water_total'],
                //     'total' => $row['total'],
                //     'tube' => $row['tube'],
                //     'panin' => $row['panin'],
                //     'bca' => $row['bca'],
                //     'cimb' => $row['cimb'],
                //     'mandiri' => $row['mandiri'],
                //     'add_charge' => $row['add_charge'],
                //     'previous_transaction' => $row['previous_transaction'],
                //     'status' => $row['status'],
                // ]);
            } else {
                BillingImportLogData::create([
                    'billing_import_log_id' => $log->id,
                    'status' => 'failed',
                    'message' => 'Failed to Import ' . trim($row['doc_no']) . ' Unit Not Found',
                ]);
            }
        }
    }

    protected function validateRow($rowData)
    {
        if (isset($rowData['no_unit'])) {
            $unit = Unit::where('no_unit', trim($rowData['no_unit']))->first();
            return !empty($unit);
        }
        return false;
    }
}
