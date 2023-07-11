<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('active_status')->default(0)->comment('0: not active, 1: active, 2: ban');
            $table->string('avatar')->nullable();
            $table->string('avatar_disk')->nullable();
            $table->string('gender')->nullable()->default(0)->comment('0: none, 1: male, 2: female, 3: other');
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable()->index();
            $table->string('language')->nullable()->index();
            $table->string('social_id')->nullable()->index();
            $table->integer('total_view')->default(0)->index();
            $table->integer('total_downloaded')->default(0)->index();
            $table->integer('total_document')->default(0)->index();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
