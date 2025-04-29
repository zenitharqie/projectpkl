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
        Schema::create('purchaseorders', function (Blueprint $table) {
            $table->id('purchaseorder_ID');
            $table->unsignedBigInteger('QID');
            $table->date('PO_Date');
            $table->string('Job_Number');
            $table->string('Contract_Number');
            $table->string('Upload_File')->nullable();
            $table->timestamps();

            $table->foreign('QID')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchaseorders');
    }
};
