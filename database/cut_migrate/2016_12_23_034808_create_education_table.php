<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('applicant_id',50);
            $table->foreign('applicant_id')
            	->references('id')->on('users')
            	->onDelete('cascade');
            $table->string('university',100);
            $table->string('degree',30); //Bachelor, Master,  Diploma, ...
            $table->string('degree_name',100)->nullable();
            $table->integer('year',4)->nullable();           
            $table->integer('city_id')->nullable();
            $table->integer('country_id')->nullable();
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
        Schema::dropIfExists('education');
    }
}
