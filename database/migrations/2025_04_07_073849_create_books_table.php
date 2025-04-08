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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('library_id');
            $table->string('isbn');
            $table->integer('edition')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->integer('total_copies');
            $table->boolean('has_ebook')->default(false);
            $table->boolean('has_paperbook')->default(true);
            $table->string('ebook_path')->nullable();
            $table->string('preview_content_path')->nullable();
            $table->text('description')->nullable();
            $table->integer('reading_progress')->default(0);
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('library_id')->references('id')->on('libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
