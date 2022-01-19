<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id');
            $table->foreign('panel_id')->on('panels')->references('id')->onDelete('CASCADE')->onUpdate("CASCADE");
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->on('agents')->references('id')->onDelete('CASCADE')->onUpdate("CASCADE");
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
        Schema::dropIfExists('panel_agents');
    }
}
