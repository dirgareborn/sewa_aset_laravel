<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Unit;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            'Ade Sri Utari',
            'Bayu Lingga Imam Hidayat',
            'Andi Fatimah Humaerah',
            'Dirgahayu',
        ];

        foreach ($employees as $index => $name) {
            $e1 = DB::table('employees')->insert(
            [
                'employee_id' => 'EMP'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $name,
                'email' => Str::slug($name, '.').'@mybpb.com',
                'role' => 'office', 
                'is_global_staff' => true
                
            ]);
            $employee = Employee::where('employee_id', 'EMP'.str_pad($index + 1, 4, '0', STR_PAD_LEFT))->first();
            $unit = Unit::inRandomOrder()->first();
            if ($employee && $unit) {
                $employee->units()->attach($unit->id, [
                    'position' => 'Staff',
                    'start_date' => Carbon::now()->subYears(2)->toDateString(),
                    'end_date' => null,
                ]);
            
        }
    }
}
}