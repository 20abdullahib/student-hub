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
        Schema::create('subjects', function (Blueprint $table) {
            $table->bigIncrements('id');  // Primary key, auto-incrementing
            $table->text('title');        // Title field (text, not null)
            $table->text('description')->nullable();  // Description field (nullable)
            $table->text('code');  // Code field (text, not null)
            $table->foreignId('department_id')  // Foreign key to departments table
                ->nullable()
                ->constrained('departments')
                ->onDelete('set null')
                ->onUpdate('cascade');  // Cascade on update
            $table->timestamps();  // Adds created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
