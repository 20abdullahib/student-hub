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
        Schema::create('generations', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('name');  // Name field
            $table->integer('year_joined');  // Year the generation joined
            $table->integer('patch');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null')->onUpdate('cascade');  // Cascade on delete and update
            $table->string('image')->default('person_icon.png');  // Image field with default value
            $table->boolean('publish')->default(false);  // Public home field with default value false
            $table->string('role');  // Role field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};
