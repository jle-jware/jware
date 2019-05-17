<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('model_type_id')->nullable();
            $table->string('rent')->nullable();
            $table->string('frequency')->nullable();
            $table->string('value')->nullable();
            $table->string('unit')->nullable();
            $table->integer('minimum_stock')->nullable();
            $table->integer('current_stock')->nullable();
            $table->string('image')->nullable();
            $table->string('utilization_type')->nullable();
            $table->string('description')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('asset_models');
    }
}
