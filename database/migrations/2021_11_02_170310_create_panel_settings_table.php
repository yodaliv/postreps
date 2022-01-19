<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble('price',10,2)->default(0);
            $table->integer('free_storage');
            $table->unsignedDouble('cost_per_unit',10,2)->default(0);
            $table->integer('frequency')->default(0);
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
        Schema::dropIfExists('panel_settings');
    }
}
