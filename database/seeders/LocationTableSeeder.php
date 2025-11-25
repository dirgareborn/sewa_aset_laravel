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
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
            $locationRecords = [
                'id' => 2,
                'name' => 'Bone',
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
            $locationRecords = [
                'id' => 3,
                'name' => 'Gunung Sari',
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
            $locationRecords = [
                'id' => 4,
                'name' => 'Pare-pare',
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
            $locationRecords = [
                'id' => 5,
                'name' => 'Parangtambung',
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
            $locationRecords = [
                'id' => 6,
                'name' => 'Tidung',
                'latitude' => '-5.5878',
                'longitude' => '120.5932',
            ],
        ];

        Location::insert($locationRecords);
    }
}
