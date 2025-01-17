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
            $table->string('name');
            $table->string('category');
            $table->string('description');
            $table->decimal('price', 8, 2);
            $table->decimal('old_price', 8, 2)->nullable(true);
            $table->string('image');
            $table->string('color');
            $table->integer('rating')->default(0);
            $table->unsignedBigInteger('author');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author')->references('id')->on('users')->onDelete('cascade');
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
