<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->primary();
            
            // $table->uuid('id')->primary();
            $table->string('login_name')->unique();
            $table->string('email')->unique();
            $table->string('telephone_no');
            $table->string('password');
            $table->integer('user_role')->default(0);
            $table->boolean('is_active')->default(0);
            $table->integer('user_type');
            $table->mediumText('api_token');
            $table->string('activation_code');
            $table->dateTime('expire_time');
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
        Schema::drop('users');
    }
}
