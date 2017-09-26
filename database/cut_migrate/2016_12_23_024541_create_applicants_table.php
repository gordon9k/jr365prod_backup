<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->increments('id')->primary();
            // $table->uuid('id')->primary();
            $table->string('user_id',50);
            $table->foreign('user_id')
            	->references('id')->on('users')
            	->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('marital_status',20);
            $table->string('gender',10);
            $table->date('date_of_birth');
            $table->string('mobile_no');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('township',50);
            $table->string('postal_code',20)->nullable();;
            $table->integer('city_id');
            $table->integer('country_id');
            $table->integer('cv_views')->nullable();;
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
        Schema::dropIfExists('applicants');
    }
}
