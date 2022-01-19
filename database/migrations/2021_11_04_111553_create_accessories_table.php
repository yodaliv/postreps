<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string("accessory_name");
            $table->unsignedInteger("quantity");
            $table->unsignedDouble("price", 10, 2);
            $table->unsignedDouble("loss_damage", 10, 2);
            $table->unsignedDouble("point_value", 10, 2);
            $table->boolean("prompt")->default(0);
            $table->boolean("message")->default(0);
            $table->boolean("inventory")->default(0);
            $table->boolean("pull_list")->default(0);
            $table->unsignedTinyInteger('default')->comment('1=ON,0=OFF')->default(1);
            $table->unsignedTinyInteger('status')->comment('1=active,0=inactive')->default(1);
            $table->text("image")->nullable();
            $table->unsignedInteger('item_id_number')->nullable();
            $table->string('item_id_code')->nullable();
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
        Schema::dropIfExists('accessories');
    }
}
