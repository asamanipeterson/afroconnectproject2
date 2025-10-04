<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('live_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stream_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('content')->nullable(false); // <--- The change is here
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_comments');
    }
}
