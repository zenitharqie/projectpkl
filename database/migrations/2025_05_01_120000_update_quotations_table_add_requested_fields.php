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
        Schema::table('quotations', function (Blueprint $table) {
            // Add new requested fields
            $table->foreignId('inquiries_id')->after('id')->constrained('inquiries')->onDelete('cascade');
            $table->foreignId('customer_id')->after('inquiries_id')->constrained('customers')->onDelete('cascade');
            $table->date('inquiry_date')->after('customer_id');
            $table->date('due_date')->nullable()->after('inquiry_date');
            $table->string('quotation_file')->nullable()->after('due_date');
            $table->string('status_quotation')->default('pending')->after('quotation_file');
            $table->string('email_customer')->after('status_quotation');
            $table->string('sales')->nullable()->after('email_customer');

            // Drop old fields no longer needed
            $table->dropColumn([
                'customer_name',
                'email',
                'company_name',
                'phone_number',
                'business_unit',
                'notes',
                'subtotal',
                'tax',
                'total',
                'attachment',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Add old fields back
            $table->string('customer_name')->after('id');
            $table->string('email')->after('customer_name');
            $table->string('company_name')->nullable()->after('email');
            $table->string('phone_number')->after('company_name');
            $table->string('business_unit')->after('phone_number');
            $table->text('notes')->nullable()->after('business_unit');
            $table->decimal('subtotal', 20, 2)->after('notes');
            $table->decimal('tax', 20, 2)->after('subtotal');
            $table->decimal('total', 20, 2)->after('tax');
            $table->string('attachment')->nullable()->after('total');

            // Drop new fields
            $table->dropForeign(['inquiries_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn([
                'inquiries_id',
                'customer_id',
                'inquiry_date',
                'due_date',
                'quotation_file',
                'status_quotation',
                'email_customer',
                'sales',
            ]);
        });
    }
};
