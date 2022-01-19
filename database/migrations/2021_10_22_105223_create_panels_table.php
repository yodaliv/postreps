<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->id();

            $table->string('panel_name');
            $table->unsignedInteger('quantity');

            $table->unsignedDouble('price',10,2)->default(0);
            $table->integer('free_storage');
            $table->unsignedDouble('cost_per_unit',10,2)->default(0);

            $table->integer('frequency');
            //
            $table->unsignedDouble('width',10,2)->default(0);
            $table->unsignedDouble('height',10,2)->default(0);
            //
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('agent_id')->on('agents')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');

            $table->unsignedTinyInteger('status')->comment('1=active,0=inactive')->default(1);
            $table->text('image_path')->nullable();

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
        Schema::dropIfExists('panels');
    }
}
