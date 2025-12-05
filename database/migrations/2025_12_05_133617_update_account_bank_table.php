<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('account_banks', function (Blueprint $table) {

            // ==== Tambah relasi service ====
            if (!Schema::hasColumn('account_banks', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('id');
                $table->foreign('service_id')
                    ->references('id')->on('services')
                    ->onDelete('cascade');
            }

            // ==== Tambah kolom type (qris / va) ====
            if (!Schema::hasColumn('account_banks', 'type')) {
                $table->enum('type', ['qris', 'va'])->default('va')->after('service_id');
            }

            // ==== Kolom untuk QRIS ====
            if (!Schema::hasColumn('account_banks', 'qris_image')) {
                $table->string('qris_image')->nullable()->after('type');
            }
            if (!Schema::hasColumn('account_banks', 'merchant_name')) {
                $table->string('merchant_name')->nullable()->after('qris_image');
            }
            if (!Schema::hasColumn('account_banks', 'merchant_id')) {
                $table->string('merchant_id')->nullable()->after('merchant_name');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_banks', function (Blueprint $table) {

            // DROP relasi & kolom
            if (Schema::hasColumn('account_banks', 'service_id')) {
                $table->dropForeign(['service_id']);
                $table->dropColumn('service_id');
            }

            if (Schema::hasColumn('account_banks', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('account_banks', 'qris_image')) {
                $table->dropColumn('qris_image');
            }
            if (Schema::hasColumn('account_banks', 'merchant_name')) {
                $table->dropColumn('merchant_name');
            }
            if (Schema::hasColumn('account_banks', 'merchant_id')) {
                $table->dropColumn('merchant_id');
            }

        });
    }
};
