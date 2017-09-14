<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refrees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('applicant_id',50);
            $table->foreign('applicant_id')
            	->references('id')->on('users')
            	->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('organization');
            $table->string('rank',50);
            $table->string('mobile_no');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('refrees');
    }
}
