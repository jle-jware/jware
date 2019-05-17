<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInsulationValueToInspectionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_results', function (Blueprint $table) {
            $table->string('insulation')->nullable()->after('band');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inspection_results', function (Blueprint $table) {
            $table->string('insulation')->nullable()->after('band');
        });
    }
}
