<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_posts', function (Blueprint $table) {
            $table->id();
            // The user who shared the post
            $table->foreignId('sharer_id')->constrained('users')->onDelete('cascade');
            // The user who received the shared post (the recipient)
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            // The post that was shared
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            // Optional: A message from the sharer
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_posts');
    }
}
