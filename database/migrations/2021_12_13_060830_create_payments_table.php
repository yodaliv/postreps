<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete("CASCADE")->onUpdate('CASCADE');
            $table->unsignedBigInteger("paid_by");
            $table->foreign('paid_by')->on('users')->references('id')->onDelete("CASCADE")->onUpdate('CASCADE');
            $table->foreignId("office_id")->constrained();
            $table->foreignId("agent_id")->constrained()->nullable();
            $table->text('card_last_four_digits'); //Last 4 digits of card number. This field must be encrypted
            $table->unsignedDouble('amount', 10, 2);
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
        Schema::dropIfExists('payments');
    }
}
