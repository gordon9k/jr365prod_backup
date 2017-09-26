<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('companies', function (Blueprint $table) {
            	$table->uuid('id')->primary();
            	$table->string('user_id');
            	$table->string('company_name');            	
            	$table->string('address');
            	$table->string('township',50);
            	$table->string('postal_code',20)->nullable();;
            	$table->integer('city_id');
            	$table->integer('country_id');
            	$table->string('mobile_no');
            	$table->string('email')->nullable();;
            	$table->string('website')->nullable();;
            	$table->string('description')->nullable();;
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
        Schema::dropIfExists('companies');
    }
}
