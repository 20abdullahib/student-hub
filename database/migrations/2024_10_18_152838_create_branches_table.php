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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('name');  // Name field
            $table->foreignId('department_id')  // Foreign key to departments table
                ->nullable()
                ->constrained('departments')
                ->onDelete('set null')
                ->onUpdate('cascade');  // Cascade on delete and update
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
