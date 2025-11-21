<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Unit;

class UnitTableSeeder extends Seeder {
    public function run(): void {
        $h = Department::where('slug','hospitality')->first();
        $food = Unit::create(['department_id'=>$h->id,'name'=>'Foodcourt & Catering','slug'=>'foodcourt-catering','type'=>'foodcourt']);
        Unit::create(['department_id'=>$h->id,'parent_id'=>$food->id,'name'=>'Kantin Universitas','slug'=>'kantin-universitas']);
        Unit::create(['department_id'=>$h->id,'parent_id'=>$food->id,'name'=>'Kantin Fakultas Teknik','slug'=>'kantin-ft']);
        $hotel = Unit::create(['department_id'=>$h->id,'name'=>'Hotel & Penginapan','slug'=>'hotel-penginapan','type'=>'hotel']);
        Unit::create(['department_id'=>$h->id,'parent_id'=>$hotel->id,'name'=>'Hotel Universitas','slug'=>'hotel-universitas']);
        Unit::create(['department_id'=>$h->id,'parent_id'=>$hotel->id,'name'=>'Rumah Susun Fakultas Ekonomi','slug'=>'rusun-fe']);
    }
}
