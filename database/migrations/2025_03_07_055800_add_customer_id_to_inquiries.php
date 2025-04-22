<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'customer_id')) {
                $table->foreignId('customer_id')->constrained()->after('id');
            }
    
            if (Schema::hasColumn('inquiries', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
        });
    }
    

    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->string('customer_name')->after('id');
        });
    }
};
