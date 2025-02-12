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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // File name
            $table->string('path'); // Dropbox path
            $table->string('link')->nullable(); // Shared link
            $table->string('file_id')->nullable(); // Shared link code
            $table->string('rlkey')->nullable(); // Security key for Dropbox shared links
            $table->unsignedBigInteger('size'); // File size in bytes
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade'); // Belongs to a subject
            $table->foreignId('dropbox_account_id')->constrained('dropbox_accounts')->onDelete('cascade'); // Stored in an account
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
