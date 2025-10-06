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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('code')->unique(); // kode unik (nomor resi/barang)
            $table->string('name');
            $table->string('room')->nullable(); // nama ruangan
            $table->string('floor')->nullable(); // lantai
            $table->enum('status', ['active', 'inactive'])->default('active'); // status perangkat
            $table->date('install_date');
            $table->date('replacement_date');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
