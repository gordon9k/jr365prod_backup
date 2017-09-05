<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
    	Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id',50);
            $table->foreign('user_id')
            	->references('id')->on('users')
            	->onDelete('cascade');
            $table->dateTime('pay_date');
            $table->dateTime('expire_date');
            $table->integer('amount');
            $table->string('description');
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
        Schema::dropIfExists('payments');
    }
}
