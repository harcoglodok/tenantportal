<?php

namespace App\Imports;

use App\Models\Billing;
use App\Models\BillingImportLog;
use App\Models\BillingImportLogData;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

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
                    Billing::where("month", $row['fin_month'])
                        ->where("year", $row['fin_year'])
                        ->delete();
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
                    $waterTotal = doubleval(trim($row['wf_mbase_amt']))+doubleval(trim($row['wu_mbase_amt']))+doubleval(trim($row['wa_mbase_amt']))+doubleval(trim($row['wa_mtax_amt']));
                    $electricTotal = doubleval(trim($row['eu_mbase_amt']));
                    $sinkFund = doubleval(trim($row['sink_chrg']));
                    $serviceCharge = doubleval(trim($row['serv_chrg']));
                    $total = $electricTotal + $waterTotal + $serviceCharge + $sinkFund;
                    Billing::create([
                        'inv_no' => trim($row['doc_no']),
                        'month' => trim($row['fin_month']),
                        'year' => trim($row['fin_year']),
                        'unit_id' => $unit->id,
                        's4_mbase_amt' => doubleval(trim($row['s4_mbase_amt'])),
                        's4_mtax_amt' => doubleval(trim($row['s4_mtax_amt'])),
                        'sd_mbase_amt' => doubleval(trim($row['sd_mbase_amt'])),
                        'service_charge' => $serviceCharge,
                        'sinking_fund' => $sinkFund,
                        'electric_previous' => doubleval(trim($row['eu_previous_read'])),
                        'electric_current' => doubleval(trim($row['eu_current_read'])),
                        'electric_read' => doubleval(trim($row['eu_read'])),
                        'electric_fixed' => doubleval(trim($row['ef_mbase_amt'])),
                        'electric_administration' => doubleval(trim($row['ec_mbase_amt'])),
                        'electric_tax' => doubleval(trim($row['ec_mtax_amt'])),
                        'electric_total' => $electricTotal,
                        'mcb' => doubleval(trim($row['meter_cd_ef'])),
                        'water_previous' => doubleval(trim($row['wu_previous_read'])),
                        'water_current' => doubleval(trim($row['wu_current_read'])),
                        'water_read' => doubleval(trim($row['wu_read'])),
                        'water_fixed' => doubleval(trim($row['wf_mbase_amt'])),
                        'water_mbase' => doubleval(trim($row['wu_mbase_amt'])),
                        'water_administration' => 10 * doubleval(trim($row['wa_mtax_amt'])),
                        'water_tax' => doubleval(trim($row['wa_mtax_amt'])),
                        'water_total' => $waterTotal,
                        'total' => $total,
                        'tube' => trim($row['meter_cd_wf']),
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
