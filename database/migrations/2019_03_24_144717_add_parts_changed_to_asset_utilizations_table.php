<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartsChangedToAssetUtilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_utilizations', function (Blueprint $table) {
            $table->boolean('parts_changed')->nullable()->after('clear_calculation');
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
            $table->boolean('parts_changed')->nullable()->after('clear_calculation');
        });
    }
}
