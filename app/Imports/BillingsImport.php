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
            $unit = $this->validateRow($row);
            if (!empty($unit)) {
                try {
                    $unit->update([
                        'business_id' => trim($row['business_id']),
                        'name' => trim($row['nama']),
                        'email' => trim($row['email_bill']),
                        'handphone' => trim($row['handphone_bill']),
                    ]);
                    Billing::create([
                        'inv_no' => trim($row['doc_no']),
                        'month' => trim($row['fin_month']),
                        'year' => trim($row['fin_year']),
                        'unit_id' => $unit->id,
                        's4_mbase_amt' => 0,
                        's4_mtax_amt' => 0,
                        'sd_mbase_amt' => 0,
                        'service_charge' => 0,
                        'sinking_fund' => 0,
                        'electric_previous' => 0,
                        'electric_current' => 0,
                        'electric_read' => 0,
                        'electric_fixed' => 0,
                        'electric_administration' => 0,
                        'electric_tax' => 0,
                        'electric_total' => 0,
                        'mcb' => 0,
                        'water_previous' => 0,
                        'water_current' => 0,
                        'water_read' => 0,
                        'water_fixed' => 0,
                        'water_mbase' => 0,
                        'water_administration' => 0,
                        'water_tax' => 0,
                        'water_total' => 0,
                        'total' => 0,
                        'tube' => '',
                        'panin' => trim($row['va_panin']),
                        'bca' => trim($row['va_bca']),
                        'cimb' => trim($row['va_cim']),
                        'mandiri' => trim($row['va_man']),
                        'add_charge' => doubleval(trim($row['add_charge'])),
                        'previous_transaction' => doubleval(trim($row['previous_transaction'])),
                        'status' => 'unpaid',
                    ]);
                    BillingImportLogData::create([
                        'billing_import_log_id' => $log->id,
                        'status' => 'success',
                        'message' => 'Successfully Import ' . trim($row['doc_no']) . ' to ' . $row['no_unit'],
                    ]);
                } catch (\Throwable $th) {
                    BillingImportLogData::create([
                        'billing_import_log_id' => $log->id,
                        'status' => 'failed',
                        'message' => 'Failed to Import ' . trim($row['doc_no']) . ' : ' . $th->getMessage(),
                    ]);
                }
            } else {
                BillingImportLogData::create([
                    'billing_import_log_id' => $log->id,
                    'status' => 'failed',
                    'message' => 'Failed to Import ' . trim($row['doc_no']) . ' : Unit Not Found',
                ]);
            }
        }
    }

    protected function validateRow($rowData)
    {
        if (isset($rowData['no_unit'])) {
            $unit = Unit::where('no_unit', trim($rowData['no_unit']))->first();
            return $unit;
        }
        return null;
    }
}
