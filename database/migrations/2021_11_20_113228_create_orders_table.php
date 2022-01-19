<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('address');

            $table->unsignedTinyInteger('property_type')->default(1)->comment("1=Exhisting Home/Condo,2=new Construction,3=Vacant Land,4=Commercial/Industrial");
            $table->unsignedTinyInteger('desired_date_type')->default(1)->comment("1=asap,2=custom_desired_date");

            $table->date('desired_date')->nullable();

            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('panel_id');
            $table->unsignedBigInteger('accessory_id');

            $table->foreign('office_id')->references('id')->on('offices')->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->foreign('post_id')->references('id')->on('posts')->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->foreign('panel_id')->references('id')->on('panels')->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->foreign('accessory_id')->references('id')->on('accessories')->onDelete("CASCADE")->onUpdate("CASCADE");

            $table->longText("comment")->nullable();

            $table->unsignedDouble('signage_fee',10,2)->default(0);
            $table->unsignedDouble('zone_fee',10,2)->default(0);
            $table->unsignedDouble('total',10,2)->default(0);

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
        Schema::dropIfExists('orders');
    }
}
