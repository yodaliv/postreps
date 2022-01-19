<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("accessory_id");
            $table->string("item_id")->index();
            $table->foreign('accessory_id')->on("accessories")->references("id")->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->unsignedBigInteger("office_id");
            $table->foreign('office_id')->on("offices")->references("id")->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->unsignedBigInteger("agent_id")->nullable();
            $table->foreign('agent_id')->on("agents")->references("id")->onDelete("CASCADE")->onUpdate("CASCADE");

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
        Schema::dropIfExists('inventory_lists');
    }
}
