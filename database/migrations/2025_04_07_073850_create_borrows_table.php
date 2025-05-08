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
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('library_id');
            $table->enum('type', ['borrow', 'reserve','return']);
            $table->timestamp('borrow_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue', 'active', 'expire','rejected']);
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
