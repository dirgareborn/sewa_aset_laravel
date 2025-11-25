<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Hapus kolom maps
            if (Schema::hasColumn('locations', 'maps')) {
                $table->dropColumn('maps');
            }

            // Tambah kolom latitude dan longitude
            $table->decimal('latitude', 10, 7)->nullable()->after('id');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Kembalikan kolom maps
            $table->string('maps')->nullable()->after('id');

            // Hapus kolom latitude dan longitude
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
