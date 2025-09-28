<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('channel_name')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('offline'); // e.g., 'live', 'offline'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('streams');
    }
}
