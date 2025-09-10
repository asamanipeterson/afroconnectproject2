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
        Schema::table('one_time_pass_codes', function (Blueprint $table) {
            $table->timestamp('expires_at')->after('code');
        });
    }

    public function down(): void
    {
        Schema::table('one_time_pass_codes', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
