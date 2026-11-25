<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->unique();
            $table->enum('type', ['anime', 'manga']);
            $table->string('genre');
            $table->decimal('rating', 4, 2);
            $table->text('synopsis');
            $table->boolean('is_available')->default(true);

            $table->unsignedInteger('total_episodes')->nullable();
            $table->string('studio')->nullable();
            $table->string('resolution')->nullable();
            $table->boolean('has_sub_indo')->nullable();

            $table->unsignedInteger('total_chapters')->nullable();
            $table->string('author')->nullable();
            $table->boolean('is_colored')->nullable();
            $table->boolean('is_completed')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
