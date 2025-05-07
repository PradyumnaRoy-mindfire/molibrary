<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::table('borrows', function (Blueprint $table) {
            // $table->enum('status', ['borrowed', 'returned', 'overdue', 'active', 'expire', 'pending'])->change();
        });
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('borrowed', 'returned', 'overdue', 'active', 'expire', 'pending')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('borrowed', 'returned', 'overdue', 'active', 'expire')");
    }
};
