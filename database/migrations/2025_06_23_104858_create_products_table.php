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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('title')->index();
            $table->string('short_desc');
            $table->string('price');
            $table->boolean('discount');
            $table->string('discount_price');
            $table->string('image');
            $table->boolean('stock');
            $table->float('star');
            $table->enum('remark', ['new','featured','bestseller','popular','trending','top','special', 'hot', 'sale','regular'])->index();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
