<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('requisition_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('handled_by')->constrained('users');
            $table->enum('type', ['IN', 'OUT']);
            $table->unsignedInteger('qty');
            $table->unsignedInteger('unit_price'); // store smallest currency, e.g. paisa
            $table->unsignedInteger('issued_qty')->default(0); // only for IN rows
            $table->timestamps();
        });
    }
};
