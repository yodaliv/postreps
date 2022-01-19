<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoryAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessory_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accessory_id');
            $table->foreign('accessory_id')->on('accessories')->references('id')->onDelete('CASCADE')->onUpdate("CASCADE");
            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->on('agents')->references('id')->onDelete('CASCADE')->onUpdate("CASCADE");
            $table->unsignedDouble('price',10,2)->default(0);
            $table->boolean("access")->default(0);
            $table->boolean("locked")->default(0);
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
        Schema::dropIfExists('accessory_agents');
    }
}
