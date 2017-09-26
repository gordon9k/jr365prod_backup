<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->string('login_name')->unique();
            // $table->string('email')->unique();
            $table->string('telephone_no')->unique();
            $table->string('password');
            $table->string('user_role')->default(0);
            $table->string('is_active');
            $table->string('user_type');
            $table->string('activation_code');
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
        Schema::dropIfExists('tmp_users');
    }
}
