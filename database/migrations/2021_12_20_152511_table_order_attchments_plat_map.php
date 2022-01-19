<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableOrderAttchmentsPlatMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_attachments', function (Blueprint $table) {
            //
            $table->boolean("plat_map")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_attachments', function (Blueprint $table) {
            //
            $table->dropColumn('plat_map');
        });
    }
}
