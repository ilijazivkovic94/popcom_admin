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
            $table->bigInteger('account_id')->nullable();
            $table->enum('user_admin_yn',array('Y','N'))->default('N')->nullable();
            $table->string('user_fname',500)->nullable();
            $table->string('user_lname',500)->nullable();
            $table->string('email',500)->unique();
            $table->string('password',500)->nullable();
            $table->enum('user_2fa_yn',array('Y','N'))->nullable();
            $table->string('created_at',20)->nullable();
            $table->string('modified_at',20)->nullable();
            $table->enum('user_active_yn',array('Y','N'))->default('N')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
