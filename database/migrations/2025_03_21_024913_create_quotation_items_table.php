<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade'); // Relasi ke tabel quotations
            $table->string('name'); // Nama item
            $table->integer('quantity'); // Jumlah item
            $table->decimal('price', 10, 2); // Harga per item
            $table->decimal('total', 10, 2); // Total harga (quantity * price)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_items');
    }
};