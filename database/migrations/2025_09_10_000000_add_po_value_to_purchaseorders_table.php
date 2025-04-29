<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->decimal('po_value', 15, 2)->nullable()->after('Contract_Number');
        });
    }

    public function down()
    {
        Schema::table('purchaseorders', function (Blueprint $table) {
            $table->dropColumn('po_value');
        });
    }
};
