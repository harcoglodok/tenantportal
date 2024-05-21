<?php

namespace App\Imports;

use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ComplaintImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', '300');
        foreach ($collection as $item) {
            $category = ComplaintCategory::find($item['complaint_type_id']);
            $unit = Unit::find($item['created_by']);
            $photo = '';
            if ($item['photo']) {
                $photo = 'complaints/' . $item['photo'];
            }
            Complaint::create([
                'category_id' => $category->id,
                'title' => 'Komplain ' . $category->title,
                'content' => $item['content'],
                'photo' => $photo,
                'status' => 'done',
                'created_by' => $unit->user_id,
                'updated_by' => $unit->user_id,
                "created_at" => $item["created_at"],
                "updated_at" => $item["updated_at"],
            ]);
        }
    }
}
