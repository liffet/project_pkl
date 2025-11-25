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

            // relasi ke tabel kategori
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // relasi ke tabel gedung (BARU)
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');

            // relasi ke tabel lantai
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');

            // relasi ke tabel ruangan
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');

            $table->string('code')->unique(); // kode unik (nomor resi/barang)
            $table->string('name');
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