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
            $table->increments('id');
            $table->integer('account_id')->unsigned()->nullable();
            $table->string('contractor_id')->nullable();
            $table->integer('job_number')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('asset_id')->nullable();
            $table->integer('asset_model_id')->nullable();
            $table->integer('machine_option')->nullable();
            $table->integer('job_type_id')->nullable();
            $table->integer('cost_labour')->nullable();
            $table->integer('material_labour')->nullable();
            $table->string('priority_id')->nullable();
            $table->integer('inspection_id')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('job_stat_id')->nullable();
            $table->dateTime('job_raised_date');
            $table->dateTime('required_date');
            $table->longText('description')->nullable();
            $table->string('has_optional_contact')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_number')->nullable();
            $table->integer('admin_close_out')->nullable();
            $table->integer('technical_manager_close_out')->nullable();
            $table->string('review')->nullable();
            $table->integer('send_email')->nullable();
            $table->string('charge_to')->nullable();
            $table->boolean('close_by_admin')->nullable();
            $table->boolean('need_approval')->nullable();
            $table->boolean('is_nuisance')->nullable();
            $table->boolean('email_sent')->nullable();
            $table->longText('comment')->nullable();
            $table->integer('raised_by')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
