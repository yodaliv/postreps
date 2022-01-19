<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('post_name');
            $table->unsignedInteger('quantity');
            $table->unsignedDouble('price');
            $table->unsignedDouble('renewal_fee');
            $table->unsignedDouble('loss_damage');
            $table->unsignedDouble('time_days');
            $table->string('point_value')->nullable();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->foreign('office_id')->on('offices')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('agent_id')->on('agents')->references('id')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->unsignedTinyInteger('default')->comment('1=ON,0=OFF')->default(1);
            $table->unsignedTinyInteger('status')->comment('1=active,0=inactive')->default(1);
            $table->text('image_path')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
