<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            
            // Pelapor
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Item yang dilaporkan rusak
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            
            // Alasan kerusakan
            $table->text('reason');
            
            // Status: pending / accepted / rejected
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
