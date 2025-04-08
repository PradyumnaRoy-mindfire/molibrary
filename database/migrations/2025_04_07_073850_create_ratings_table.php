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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->enum('rate', ['book', 'library']);
            $table->unsignedBigInteger('borrow_id');
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->timestamps();

            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
