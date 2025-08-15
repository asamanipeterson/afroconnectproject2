<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // This creates an unsignedBigInteger primary key
            $table->string('title');
            $table->decimal('price', 12, 2); // Updated to match the new price column definition
            $table->string('category');
            $table->string('condition');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
