<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableOrderAttachmentsAddAccessoryId extends Migration
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
            $table->unsignedBigInteger("accessory_id")->nullable();
            $table->foreign('accessory_id')->on('accessories')->references('id')->onDelete("CASCADE")->onUpdate("CASCADE");
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
            $table->dropColumn('accessory_id');
        });
    }
}
