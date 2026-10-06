<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->string('unit', 20)->default('pcs');

            $table->enum('condition', ['Baik', 'Cukup', 'Perlu Perbaikan'])
                  ->default('Baik');

            $table->string('image_path')->nullable();
            $table->enum('status', ['available', 'unavailable'])
                  ->default('available');

            // Lokasi barang (snapshot dari lokasi supplier saat dibuat)
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->index(['status', 'category_id']);
            $table->index(['latitude', 'longitude']);
            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};