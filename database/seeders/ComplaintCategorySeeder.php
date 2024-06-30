<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComplaintCategory;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ComplaintCategory::create([
            'id' => 1,
            'title' => 'Mechanical',
        ]);
        ComplaintCategory::create([
            'id' => 2,
            'title' => 'Electrical',
        ]);
        ComplaintCategory::create([
            'id' => 3,
            'title' => 'Telephone',
        ]);
        ComplaintCategory::create([
            'id' => 4,
            'title' => 'CCTV',
        ]);
        ComplaintCategory::create([
            'id' => 5,
            'title' => 'House Keeping',
        ]);
        ComplaintCategory::create([
            'id' => 6,
            'title' => 'Parkir',
        ]);
        ComplaintCategory::create([
            'id' => 7,
            'title' => 'Security',
        ]);
        ComplaintCategory::create([
            'id' => 8,
            'title' => 'Civil',
        ]);
        ComplaintCategory::create([
            'id' => 9,
            'title' => 'Lain-Lain',
        ]);
    }
}
