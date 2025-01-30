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
        Schema::create('branch_subject', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();  // Optional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_subject');
    }
};
