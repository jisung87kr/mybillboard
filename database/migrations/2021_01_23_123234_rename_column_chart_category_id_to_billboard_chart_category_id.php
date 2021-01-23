<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnChartCategoryIdToBillboardChartCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billboard_charts', function (Blueprint $table) {
            $table->dropColumn('chart_category_id');
            $table->foreignId('billboard_chart_category_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billboard_charts', function (Blueprint $table) {
            $table->dropForeign('billboard_charts_billboard_chart_category_id_foreign');
        });
    }
}
