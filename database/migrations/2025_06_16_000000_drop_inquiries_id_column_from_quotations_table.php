<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu jika masih ada
            if (Schema::hasColumn('quotations', 'inquiries_id')) {
                $table->dropForeign(['inquiries_id']); // nama field, bukan nama constraint
                $table->dropColumn('inquiries_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('inquiries_id')->constrained('inquiries')->onDelete('cascade');
        });
    }
};
