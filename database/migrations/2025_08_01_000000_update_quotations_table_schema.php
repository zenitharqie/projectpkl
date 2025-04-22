<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuotationsTableSchema extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop old columns that conflict or are unused
            if (Schema::hasColumn('quotations', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('quotations', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('quotations', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('quotations', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('quotations', 'business_unit')) {
                $table->dropColumn('business_unit');
            }
            if (Schema::hasColumn('quotations', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('quotations', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('quotations', 'tax')) {
                $table->dropColumn('tax');
            }
            if (Schema::hasColumn('quotations', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('quotations', 'attachment')) {
                $table->dropColumn('attachment');
            }

            // Add new columns as per model and controller
            if (!Schema::hasColumn('quotations', 'inquiry_id')) {
                $table->unsignedBigInteger('inquiry_id')->nullable()->index();
            }
            if (!Schema::hasColumn('quotations', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->index();
            }
            if (!Schema::hasColumn('quotations', 'inquiry_date')) {
                $table->date('inquiry_date')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'quotation_file')) {
                $table->string('quotation_file')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'status_quotation')) {
                $table->string('status_quotation')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'email_customer')) {
                $table->string('email_customer')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'sales')) {
                $table->string('sales')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'quotation_number')) {
                $table->string('quotation_number')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable();
            }

            // Add foreign keys if needed (optional)
            // $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('set null');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'inquiry_id',
                'customer_id',
                'inquiry_date',
                'due_date',
                'quotation_file',
                'status_quotation',
                'email_customer',
                'sales',
                'quotation_number',
                'email_sent_at',
            ]);

            // Re-add old columns (simplified, types may need adjustment)
            $table->string('customer_name')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('business_unit')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 20, 2)->nullable();
            $table->decimal('tax', 20, 2)->nullable();
            $table->decimal('total', 20, 2)->nullable();
            $table->string('attachment')->nullable();
        });
    }
}
