<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Unit;

class EmployeeTableSeeder extends Seeder {
    public function run(): void {
        $e1 = Employee::create(['employee_id'=>'EMP0001','name'=>'Budi Kepala Hosp','email'=>'budi@unm.ac.id','role'=>'office','is_global_staff'=>true]);
        $e2 = Employee::create(['employee_id'=>'EMP0002','name'=>'Ani Manager Foodcourt','email'=>'ani@unm.ac.id','role'=>'unit']);
        $unit = Unit::where('slug','kantin-universitas')->first();
        if($unit) $e2->units()->attach($unit->id,['position'=>'Manager','start_date'=>now()]);
    }
}
