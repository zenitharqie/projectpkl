<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Remove item_code and quantity columns
            if (Schema::hasColumn('inquiries', 'item_code')) {
                $table->dropColumn('item_code');
            }
            if (Schema::hasColumn('inquiries', 'quantity')) {
                $table->dropColumn('quantity');
            }
            // Rename due_date to inquiry_date
            if (Schema::hasColumn('inquiries', 'due_date')) {
                $table->renameColumn('due_date', 'inquiry_date');
            }
            // Add sales_name column
            if (!Schema::hasColumn('inquiries', 'sales_name')) {
                $table->string('sales_name')->nullable()->after('customer_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Add back item_code and quantity columns
            if (!Schema::hasColumn('inquiries', 'item_code')) {
                $table->string('item_code')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('inquiries', 'quantity')) {
                $table->integer('quantity')->after('description');
            }
            // Rename inquiry_date back to due_date
            if (Schema::hasColumn('inquiries', 'inquiry_date')) {
                $table->renameColumn('inquiry_date', 'due_date');
            }
            // Drop sales_name column
            if (Schema::hasColumn('inquiries', 'sales_name')) {
                $table->dropColumn('sales_name');
            }
        });
    }
};
