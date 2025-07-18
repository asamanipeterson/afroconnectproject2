<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('story_type', ['image', 'video', 'text']);
            $table->string('media_path')->nullable(); // for image/video
            $table->text('text_content')->nullable(); // for text stories
            $table->string('background')->nullable(); // background color for text stories
            $table->string('caption')->nullable();
            $table->timestamps();
            $table->timestamp('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
