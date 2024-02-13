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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name')->nullable(false)->change();
            $table->unsignedBigInteger('user_type_id')->nullable(false)->change();

            $table->boolean('google_account')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name')->nullable()->change();
            $table->unsignedBigInteger('user_type_id')->nullable()->change();

            $table->dropColumn('google_account');
        });
    }
};
