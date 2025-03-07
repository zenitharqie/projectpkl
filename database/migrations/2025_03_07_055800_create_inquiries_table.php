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
    Schema::create('inquiries', function (Blueprint $table) {
        $table->id();
        $table->string('customer_name');
        $table->string('item_code')->nullable();
        $table->text('description');
        $table->integer('quantity');
        $table->date('due_date');
        $table->string('status')->default('pending'); // Ubah tipe data menjadi string
        $table->string('document')->nullable(); // Tambahkan kolom document
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
