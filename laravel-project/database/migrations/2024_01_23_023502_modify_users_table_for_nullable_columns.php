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
            $table->dropForeign(['group_id']);
            $table->dropColumn(['login_id', 'password', 'group_id', 'gender']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('login_id', 22)->nullable();
            $table->string('password', 80)->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('gender', 191)->nullable();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id_foreign']); // group_idに対する外部キー制約の名前を確認してください。
            $table->dropColumn(['login_id', 'password', 'group_id', 'gender']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('login_id', 22);
            $table->string('password', 80);
            $table->unsignedBigInteger('group_id');
            $table->string('gender', 191);

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }
};
