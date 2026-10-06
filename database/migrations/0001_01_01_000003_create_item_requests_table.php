<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                  ->constrained('items')
                  ->cascadeOnDelete();

            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->unsignedInteger('quantity');

            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->text('note')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['item_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};