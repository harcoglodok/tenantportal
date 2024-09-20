<?php

namespace App\Jobs;

use App\Models\Unit;
use App\Models\Billing;
use Illuminate\Bus\Queueable;
use App\Models\BillingImportLog;
use App\Models\BillingImportLogData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportBilling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($this->file);

        $log = BillingImportLog::latest()->take(1)->first();
        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getIndex() === 0) {
                $no = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    // perulangan membaca baris excel
                    if ($no == 0) {
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
                            $waterTotal = doubleval(trim($row['wf_mbase_amt'])) + doubleval(trim($row['wu_mbase_amt'])) + doubleval(trim($row['wa_mbase_amt'])) + doubleval(trim($row['wa_mtax_amt']));
                            $electricTotal = doubleval(trim($row['eu_mbase_amt']));
                            $sinkFund = doubleval(trim($row['sf_mbase_amt']));
                            $serviceCharge = doubleval(trim($row['serv_chrg']));
                            $total = $electricTotal + $waterTotal + $serviceCharge + $sinkFund;
                            Billing::create([
                                'inv_no' => trim($row['doc_no']),
                                'month' => trim($row['fin_month']),
                                'year' => trim($row['fin_year']),
                                'unit_no' => $unit->no_unit,
                                'name' => trim($row['nama']),
                                's4_mbase_amt' => doubleval(trim($row['s4_mbase_amt'])),
                                's4_mtax_amt' => doubleval(trim($row['s4_mtax_amt'])),
                                'sd_mbase_amt' => doubleval(trim($row['sd_mbase_amt'])),
                                'service_charge' => $serviceCharge,
                                'sinking_fund' => $sinkFund,
                                'electric_previous' => doubleval(trim($row['eu_previous_read'])),
                                'electric_current' => doubleval(trim($row['eu_current_read'])),
                                'electric_read' => doubleval(trim($row['eu_read'])),
                                'electric_fixed' => doubleval(trim($row['ef_mbase_amt'])),
                                'electric_mbase' => doubleval(trim($row['eu_mbase_amt'])),
                                'electric_administration' => doubleval(trim($row['ec_mbase_amt'])),
                                'electric_tax' => doubleval(trim($row['ec_mtax_amt'])),
                                'electric_total' => $electricTotal,
                                'mcb' => trim($row['meter_cd_ef']),
                                'water_previous' => doubleval(trim($row['wu_previous_read'])),
                                'water_current' => doubleval(trim($row['wu_current_read'])),
                                'water_read' => doubleval(trim($row['wu_read'])),
                                'water_fixed' => doubleval(trim($row['wf_mbase_amt'])),
                                'water_mbase' => doubleval(trim($row['wu_mbase_amt'])),
                                'water_administration' => doubleval(trim($row['wa_mbase_amt'])),
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
                                'status' => $row['status'] ?? 'unpaid',
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

                    $no++;
                }
                break;
            }
        }

        $reader->close();


        $successData = BillingImportLogData::where('billing_import_log_id', $log->id)
            ->where('status', 'success')
            ->count();
        $failedData = BillingImportLogData::where('billing_import_log_id', $log->id)
            ->where('status', 'failed')
            ->count();

        Notification::make()
            ->success()
            ->title('Billings Imported')
            ->body('Successfully import ' . $successData . ' Invoice' . ($failedData ? (' & Failed to Import ' . $failedData . ' Invoice') : ''))
            ->send();
    }

    protected function validateRow($noUnit)
    {
        if (isset($noUnit)) {
            $unit = Unit::where('no_unit', trim($noUnit))->first();
            return $unit;
        }
        return null;
    }
}
