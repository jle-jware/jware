<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeakageValueToInspectionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspection_results', function (Blueprint $table) {
            $table->string('leakage')->nullable()->after('insulation');
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
            $table->string('leakage')->nullable()->after('insulation');
        });
    }
}
