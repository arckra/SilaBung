<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demand_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demand_list_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('distance_km', 8, 3)->nullable();
            $table->boolean('selected')->default(true);
            $table->timestamps();

            $table->index('supplier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demand_allocations');
    }
};