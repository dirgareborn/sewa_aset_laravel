<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Product;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hospitality = Organization::create(['name'=>'Hospitality','type'=>'department']);
        $tech = Organization::create(['name'=>'Teknologi','type'=>'department']);
        $design = Organization::create(['name'=>'Seni & Desain','type'=>'department']);

        // Unit Bisnis Hospitality
        $foodcourt = Category::create(['category_name'=>'Foodcourt','organization_id'=>$hospitality->id]);
        $hotel = Category::create(['category_name'=>'Hotel & Penginapan','organization_id'=>$hospitality->id]);

        // Sub-unit Foodcourt
        $kantin_univ = Category::create(['category_name'=>'Kantin Universitas','organization_id'=>$hospitality->id,'parent_id'=>$foodcourt->id]);
        $kantin_fak = Category::create(['category_name'=>'Kantin Fakultas Teknik','organization_id'=>$hospitality->id,'parent_id'=>$foodcourt->id]);
        $kantin_jur = Category::create(['category_name'=>'Kantin Jurusan Teknik Elektro','organization_id'=>$hospitality->id,'parent_id'=>$foodcourt->id]);

        // Sub-unit Hotel
        $hotel_univ = Category::create(['category_name'=>'Hotel Universitas','organization_id'=>$hospitality->id,'parent_id'=>$hotel->id]);
        $rusun_fe = Category::create(['category_name'=>'Rumah Susun Fakultas Ekonomi','organization_id'=>$hospitality->id,'parent_id'=>$hotel->id]);

        // Produk contoh
        Product::create(['category_id'=>$kantin_univ->id,
        'location_id'=>1,
        'product_name'=>'Menu Nasi Goreng','product_price'=>10000]);
        Product::create(['category_id'=>$hotel_univ->id,'location_id'=>1,'product_name'=>'Sewa Kamar Deluxe','product_price'=>500000]);

        // Pegawai contoh
        $head_hosp = Employee::create(['employee_id'=>'EMP001','name'=>'Budi Kepala Hospitality','email'=>'budi@unm.ac.id']);
        $manager_foodcourt = Employee::create(['employee_id'=>'EMP002','name'=>'Ani Manager Foodcourt','email'=>'ani@unm.ac.id']);

        $hospitality->update(['head_id'=>$head_hosp->id]);
        $kantin_univ->employees()->attach($manager_foodcourt->id,['position'=>'Manager','start_date'=>now()]);
    
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(AdminTableSeeder::class);
        // $this->call(CmsPageTableSeeder::class);
        // $this->call(CategoryTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        // $this->call(ProductTableSeeder::class);
        $this->call(ProductsImagesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(ProfilWebsitesTableSeeder::class);
        $this->call(InformationsTableSeeder::class);
        // $this->call(EmployeeSeeder::class);

    }
}
