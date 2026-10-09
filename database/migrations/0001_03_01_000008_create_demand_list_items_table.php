<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demand_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demand_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('search_term')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit', 20)->default('pcs');
            $table->enum('status', ['pending', 'full', 'partial', 'empty'])
                  ->default('pending');
            $table->unsignedInteger('fulfilled_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demand_list_items');
    }
};