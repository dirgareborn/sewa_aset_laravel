<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder {
    public function run(): void {
        $names = ['Hospitality','Teknologi','Seni dan Desain','Education Development','Konsultan'];
        foreach($names as $n) {
            Department::create(['name'=>$n,'slug'=>Str::slug($n),'description'=>$n]);
        }
    }
}
