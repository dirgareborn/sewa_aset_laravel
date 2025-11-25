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
        Schema::table('payments', function (Blueprint $table) {
            // admin yang melakukan verifikasi pembayaran
            $table->unsignedBigInteger('verified_by_admin_id')->nullable()->after('status');
            // waktu verifikasi
            $table->timestamp('verified_at')->nullable()->after('verified_by_admin_id');
            // opsional: catatan verifikator
            $table->string('verifier_note')->nullable()->after('verified_at');

            $table->foreign('verified_by_admin_id')
                ->references('id')->on('admins')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by_admin_id']);
            $table->dropColumn(['verified_by_admin_id', 'verified_at', 'verifier_note']);
        });
    }
};
