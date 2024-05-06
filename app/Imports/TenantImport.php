<?php

namespace App\Imports;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TenantImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', '300');
        foreach ($collection as $item) {
            $user = User::whereEmail($item["email"])->first();
            if (!$user) {
                $user = User::create([
                    "name" => $item["email"],
                    "email" => $item["email"],
                    "password" => $item["password_hash"],
                    "religion" => $item["religion"] != 'NULL' ? $item["religion"] : null,
                    "birthdate" => $item["birthday"] != 'NULL' ? $item["birthday"] : null,
                    "device_token" => $item["device_token"] != 'NULL' ? $item["device_token"] : null,
                    "verified_at" => now(),
                    "verified_by" => 1,
                    "role" => "tenant",
                    "created_at" => $item["created_at"],
                    "updated_at" => $item["updated_at"],
                ]);
            }
            Unit::create([
                "id" => $item["id"],
                "no_unit" => $item["username"],
                "user_id" => $user->id,
                "created_at" => $item["created_at"],
                "updated_at" => $item["updated_at"],
            ]);
        }
    }
}
