<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('job_id')->unsigned()->nullable();
            $table->string('swmc')->nullable();
            $table->string('job_date')->nullable();
            $table->string('timein')->nullable();
            $table->string('timeout')->nullable();
            $table->string('onstie')->nullable();
            $table->string('travel_hours')->nullable();
            $table->string('total_kms')->nullable();
            $table->string('waiting_time')->nullable();
            $table->string('waiting_reason')->nullable();
            $table->longText('comment')->nullable();
            $table->string('total_cup_count')->nullable();
            $table->boolean('run_flush')->nullable();
            $table->boolean('insulation')->nullable();
            $table->boolean('work_area')->nullable();
            $table->boolean('product_checked')->nullable();
            $table->string('customer_signature')->nullable();
            $table->boolean('is_complete')->nullable();
            $table->string('image')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('second_trip')->nullable();
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
        Schema::dropIfExists('job_sheets');
    }
}
