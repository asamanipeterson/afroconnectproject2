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
        Schema::table('messages', function (Blueprint $table) {
            // Check if the 'type' column exists before trying to add it
            if (!Schema::hasColumn('messages', 'type')) {
                $table->string('type')->default('text')->after('body');
            }
            // Check if 'audio_path' exists before adding it
            if (!Schema::hasColumn('messages', 'audio_path')) {
                $table->string('audio_path')->nullable()->after('type');
            }

            // Add the remaining columns, assuming the previous migration didn't add them
            if (!Schema::hasColumn('messages', 'image_path')) {
                $table->string('image_path')->nullable()->after('audio_path');
            }
            if (!Schema::hasColumn('messages', 'video_path')) {
                $table->string('video_path')->nullable()->after('image_path');
            }

            // This is likely what caused your first error.
            // If the body is NOT nullable, you must change it to be so
            // This change can only be done if the 'body' column is not a simple 'text' type
            // If it is, you'll need the doctrine/dbal package
            $table->text('body')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['type', 'audio_path', 'image_path', 'video_path']);

            // Revert the 'body' column back to not nullable
            // This also requires doctrine/dbal
            $table->text('body')->nullable(false)->change();
        });
    }
};
