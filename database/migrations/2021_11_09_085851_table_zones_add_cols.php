<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableZonesAddCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zones', function (Blueprint $table) {
            //
            $table->string("name")->nullable()->after('id');
            $table->unsignedDouble("zone_fee")->default(0);
            $table->unsignedInteger("installer_points")->default(0);
            $table->boolean("su")->default(0);
            $table->boolean("m")->default(0);
            $table->boolean("tu")->default(0);
            $table->boolean("w")->default(0);
            $table->boolean("th")->default(0);
            $table->boolean("f")->default(0);
            $table->boolean("sa")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zones', function (Blueprint $table) {
            //
        });
    }
}
