<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClearCalculationToAssetUtilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_utilizations', function (Blueprint $table) {
            $table->boolean('clear_calculation')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_utilizations', function (Blueprint $table) {
            $table->boolean('clear_calculation')->nullable()->after('value');
        });
    }
}
