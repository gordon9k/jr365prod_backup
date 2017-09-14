<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id',50);            
            $table->foreign('user_id')
      				->references('id')->on('users')
      				->onDelete('cascade');
            $table->string('name');
            $table->string('mobile_no');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('township',50);
            $table->string('postal_code',20)->nullable();
            $table->integer('city_id');
            $table->integer('country_id');
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
        Schema::dropIfExists('employers');
    }
}
