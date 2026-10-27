<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menyimpan path file dokumen
            $table->string('supplier_document_path')->nullable()->after('phone');
            // Menyimpan tipe (mahasiswa / alumni)
            $table->enum('supplier_type', ['mahasiswa', 'alumni'])->nullable()->after('supplier_document_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['supplier_document_path', 'supplier_type']);
        });
    }
};