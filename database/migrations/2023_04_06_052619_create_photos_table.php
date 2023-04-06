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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('caption')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('photo_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
            $table->string('tag_name');
        });

        Schema::create('photo_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_likes');
        Schema::dropIfExists('photo_tags');
        Schema::dropIfExists('photos');
    }
};
