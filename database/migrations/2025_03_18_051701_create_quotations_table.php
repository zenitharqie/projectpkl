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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email');
            $table->string('company_name')->nullable();
            $table->string('phone_number');
            $table->string('business_unit');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 20, 2); // Diperbesar untuk menghindari error
            $table->decimal('tax', 20, 2);
            $table->decimal('total', 20, 2);
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
