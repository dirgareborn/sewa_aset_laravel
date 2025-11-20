<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locationRecords = [
            [
                'id' => 1,
                'name' => 'Banta-bantaeng',
                'maps' => '-',
            ],
            $locationRecords = [
                'id' => 2,
                'name' => 'Bone',
                'maps' => '-',
            ],
            $locationRecords = [
                'id' => 3,
                'name' => 'Gunung Sari',
                'maps' => '-',
            ],
            $locationRecords = [
                'id' => 4,
                'name' => 'Pare-pare',
                'maps' => '-',
            ],
            $locationRecords = [
                'id' => 5,
                'name' => 'Parangtambung',
                'maps' => '-',
            ],
            $locationRecords = [
                'id' => 6,
                'name' => 'Tidung',
                'maps' => '-',
            ],
        ];

        Location::insert($locationRecords);
    }
}
