<?php

namespace App\Imports;

use App\Models\Unit;
use App\Models\Billing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OldBillingImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Billing::create([
                'inv_no' => $row['code'],
                'month' => $row['month'],
                'year' => $row['year'],
                'unit_no' => $row['unit_number'],
                's4_mbase_amt' => doubleval($row['s4_mbase_amt']),
                's4_mtax_amt' => doubleval($row['s4_mtax_amt']),
                'sd_mbase_amt' => doubleval($row['sd_mbase_amt']),
                'service_charge' => doubleval($row['service_charge']),
                'sinking_fund' => doubleval($row['sinking_fund']),
                'electric_previous' => doubleval($row['electric_previous']),
                'electric_current' => doubleval($row['electric_current']),
                'electric_read' => doubleval($row['electric_read']),
                'electric_fixed' => doubleval($row['electric_fixed']),
                'electric_administration' => doubleval($row['electric_administration']),
                'electric_tax' => doubleval($row['electric_tax']),
                'electric_total' => doubleval($row['electric_total']),
                'mcb' => $row['mcb'] ?? '',
                'water_previous' => doubleval($row['water_previous']),
                'water_current' => doubleval($row['water_current']),
                'water_read' => doubleval($row['water_read']),
                'water_fixed' => doubleval($row['water_fixed']),
                'water_mbase' => doubleval($row['water_mbase']),
                'water_administration' => doubleval($row['water_administration']),
                'water_tax' => doubleval($row['water_tax']),
                'water_total' => doubleval($row['water_total']),
                'total' => doubleval($row['total']),
                'tube' => $row['tube'] ?? '',
                'panin' => $row['panin'] ?? '',
                'bca' => $row['bca'] ?? '',
                'cimb' => $row['cimb'] ?? '',
                'mandiri' => $row['mandiri'] ?? '',
                'add_charge' => doubleval($row['add_charge']),
                'previous_transaction' => doubleval($row['previous_transaction']),
                'status' => 'paid',
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
        }
    }
}
