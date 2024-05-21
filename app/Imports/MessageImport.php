<?php

namespace App\Imports;

use App\Models\Unit;
use App\Models\Message;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MessageImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', '300');
        foreach ($collection as $item) {
            if ($item['tenant_store'] == "*") {
                $unit = Unit::find($item['created_by']);
                $photo = '';
                if ($item['photo']) {
                    $photo = 'complaints/' . $item['photo'];
                }
                Message::create([
                    'title' => $item['title'],
                    'photo' => $photo,
                    'content' => $item['message'],
                    'created_by' => $unit->user_id,
                    'updated_by' => $unit->user_id,
                    "created_at" => $item["created_at"],
                    "updated_at" => $item["updated_at"],
                ]);
            }
        }
    }
}
