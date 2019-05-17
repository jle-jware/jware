<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('agent_id')->nullable();
            $table->string('name')->nullable();
            $table->string('serial')->nullable();
            $table->string('bmb')->nullable();
            $table->integer('cost')->nullable();            
            $table->integer('asset_model_id')->nullable();
            $table->integer('asset_stat_id')->nullable();
            $table->string('specific_location')->nullable();
            $table->integer('machine_option')->nullable();
            $table->string('description')->nullable();
            $table->integer('is_charged')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
