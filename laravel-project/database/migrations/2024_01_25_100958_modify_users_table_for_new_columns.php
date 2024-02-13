<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['google_account', 'login_id', 'password', 'group_id', 'gender']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('login_id', 22);
            $table->string('password', 80);
            $table->unsignedBigInteger('group_id');
            $table->string('gender', 191);

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['login_id', 'password', 'group_id', 'gender']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('google_account')->default(false);
            $table->string('login_id', 22)->nullable();
            $table->string('password', 80)->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('gender', 191)->nullable();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }
};

