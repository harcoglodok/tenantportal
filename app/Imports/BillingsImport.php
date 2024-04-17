<?php

namespace App\Imports;

use App\Models\Billing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;

class BillingsImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithEvents
{
    public function model(array $row)
    {
        return new Billing([
            'name' => $row['name'],
            'email' => $row['email'],
            'address' => $row['address'],
            'phone_number' => $row['phone_number'],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $rows = $event->getConcernable()->toArray();

                foreach ($rows as $rowData) {
                    if ($this->validateRow($rowData)) {
                        // Baris valid, lakukan tindakan yang sesuai
                    } else {
                        // Baris tidak valid, tangani kesalahan
                    }
                }
            }
        ];
    }

    protected function validateRow($rowData)
    {
        // Lakukan validasi khusus untuk setiap baris di sini
        // Kembalikan true jika valid, false jika tidak
    }
}
