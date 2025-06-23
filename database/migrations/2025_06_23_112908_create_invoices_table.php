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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('total');
            $table->string('vat');
            $table->string('discount');
            $table->string('payable');
            $table->string('cus_details');
            $table->string('ship_details');
            $table->string('shipping_method');
            $table->string('tran_id');
            $table->string('val_id');
            $table->enum('delivery_status', ['pending', 'processing','completed'])->default('pending');
            $table->string('payment_status');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
