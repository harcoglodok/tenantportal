<?php

namespace App\Jobs;

use App\Models\Unit;
use App\Models\Billing;
use Illuminate\Bus\Queueable;
use App\Models\BillingImportLog;
use App\Traits\PushNotification;
use App\Models\BillingImportLogData;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportBilling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PushNotification;

    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    // 0 => doc_no
    // 1 => no_unit
    // 2 => nama
    // 3 => email_bill
    // 4 => handphone_bill
    // 5 => business_id
    // 6 => fin_month
    // 7 => fin_year
    // 8 => wu_previous_read
    // 9 => wu_current_read
    // 10 => eu_previous_read
    // 11 => eu_current_read
    // 12 => wf_mbase_amt
    // 13 => ef_mbase_amt
    // 14 => s4_mbase_amt
    // 15 => wu_mbase_amt
    // 16 => eu_mbase_amt
    // 17 => ec_mtax_amt
    // 18 => wa_mtax_amt
    // 19 => s4_mtax_amt
    // 20 => sd_mbase_amt
    // 21 => area
    // 22 => wa_mbase_amt
    // 23 => ec_mbase_amt
    // 24 => doc_no2
    // 25 => sf_mbase_amt
    // 26 => sink_chrg
    // 27 => serv_chrg
    // 28 => meter_cd_ef
    // 29 => gov_charge_ef
    // 30 => meter_cd_wf
    // 31 => gov_charge_wf
    // 32 => rate_charge_ef
    // 33 => rate_charge_wf
    // 34 => va_bca
    // 35 => va_man
    // 36 => va_cim
    // 37 => va_panin
    // 38 => eu_read
    // 39 => wu_read
    // 40 => add_charge
    // 41 => previous_transaction
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
                    $cells = $row->toArray();
                    if ($no == 0) {
                        $pastData = Billing::where("month", $cells[6])
                            ->where("year", $cells[7])
                            ->get();
                        if ($pastData->count() > 0) {
                            Billing::where("month", $cells[6])
                                ->where("year", $cells[7])
                                ->delete();
                        }
                    }
                    $unit = $this->validateRow($cells[1]);
                    if (!empty($unit)) {
                        try {
                            $unit->update([
                                'business_id' => trim($cells[5]),
                                'name' => trim($cells[2]),
                                'email' => trim($cells[3]),
                                'handphone' => trim($cells[4]),
                            ]);
                            $waterTotal = doubleval(trim($cells[12]))
                                + doubleval(trim($cells[15]))
                                + doubleval(trim($cells[22]))
                                + doubleval(trim($cells[18]));
                            $electricTotal = doubleval(trim($cells[16]));
                            $sinkFund = doubleval(trim($cells[25]));
                            $serviceCharge = doubleval(trim($cells[27]));
                            $total = $electricTotal + $waterTotal + $serviceCharge + $sinkFund;
                            Billing::create([
                                'inv_no' => trim($cells[0]),
                                'month' => trim($cells[6]),
                                'year' => trim($cells[7]),
                                'unit_no' => $unit->no_unit,
                                'name' => trim($cells[2]),
                                's4_mbase_amt' => doubleval(trim($cells[14])),
                                's4_mtax_amt' => doubleval(trim($cells[19])),
                                'sd_mbase_amt' => doubleval(trim($cells[20])),
                                'service_charge' => $serviceCharge,
                                'sinking_fund' => $sinkFund,
                                'electric_previous' => doubleval(trim($cells[10])),
                                'electric_current' => doubleval(trim($cells[11])),
                                'electric_read' => doubleval(trim($cells[38])),
                                'electric_fixed' => doubleval(trim($cells[13])),
                                'electric_mbase' => doubleval(trim($cells[16])),
                                'electric_administration' => doubleval(trim($cells[23])),
                                'electric_tax' => doubleval(trim($cells[17])),
                                'electric_total' => $electricTotal,
                                'mcb' => trim($cells[28]),
                                'water_previous' => doubleval(trim($cells[8])),
                                'water_current' => doubleval(trim($cells[9])),
                                'water_read' => doubleval(trim($cells[39])),
                                'water_fixed' => doubleval(trim($cells[12])),
                                'water_mbase' => doubleval(trim($cells[15])),
                                'water_administration' => doubleval(trim($cells[22])),
                                'water_tax' => doubleval(trim($cells[18])),
                                'water_total' => $waterTotal,
                                'total' => $total,
                                'tube' => trim($cells[30]),
                                'panin' => trim($cells[37]),
                                'bca' => trim($cells[34]),
                                'cimb' => trim($cells[36]),
                                'mandiri' => trim($cells[35]),
                                'add_charge' => doubleval(trim($cells[40])),
                                'previous_transaction' => doubleval(trim($cells[41])),
                                'status' => $cells['status'] ?? 'unpaid',
                            ]);
                            BillingImportLogData::create([
                                'billing_import_log_id' => $log->id,
                                'status' => 'success',
                                'message' => 'Successfully Import ' . trim($cells[0]) . ' to ' . $cells[1],
                            ]);
                            if ($unit->user->device_token) {
                                $this->sendPushNotification(
                                    $unit->user->device_token,
                                    'Informasi Tagihan',
                                    'Tagihan anda bulan ' . $this->getMonthNameIndonesia($cells[6]) . ' tahun ' . $cells[7] . ' sebesar Rp ' . number_format($total, 0, ',', '.') . ' mohon untuk dilakukan pembayaran'
                                );
                            }
                        } catch (\Throwable $th) {
                            BillingImportLogData::create([
                                'billing_import_log_id' => $log->id,
                                'status' => 'failed',
                                'message' => 'Failed to Import ' . trim($cells[0]) . ' : ' . $th->getMessage(),
                            ]);
                        }
                    } else {
                        BillingImportLogData::create([
                            'billing_import_log_id' => $log->id,
                            'status' => 'failed',
                            'message' => 'Failed to Import ' . trim($cells[0]) . ' : Unit Not Found',
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

    protected function getMonthNameIndonesia($monthNumber)
    {
        $monthNames = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        return $monthNames[$monthNumber] ?? $monthNumber;
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
