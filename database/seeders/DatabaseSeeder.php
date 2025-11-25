<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(CmsPageTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(AdminTableSeeder::class);
        // $this->call(ProductsImagesTableSeeder::class);
        $this->call(BannersTableSeeder::class);
        $this->call(ProfilWebsitesTableSeeder::class);
        $this->call(InformationsTableSeeder::class);

    }
}
