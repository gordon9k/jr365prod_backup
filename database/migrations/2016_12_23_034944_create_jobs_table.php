<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employer_id',50);
            $table->foreign('employer_id')
            	->references('id')->on('users')
            	->onDelete('cascade');
            $table->integer('job_category_id');
            $table->integer('job_nature_id');
            $table->string('job_title',50);
            $table->string('company_name',100);
            $table->string('salary_range',50);
            $table->string('summary')->nullable();
            $table->string('description')->nullable();
            $table->string('requirement')->nullable();
            $table->string('township',50)->nullable();
            $table->integer('city_id',50)->nullable();
            $table->integer('country_id',50)->nullable();
            $table->date('open_date');
            $table->date('close_date');
            $table->string('contact_info');
            $table->boolean('is_active');
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
        Schema::dropIfExists('jobs');
    }
}
