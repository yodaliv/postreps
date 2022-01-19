<?php

use App\Models\Office;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('name');
            $table->string('primary_contact')->nullable();
            $table->string('website')->nullable();
            $table->unsignedTinyInteger('inactive')->comment('0=Active,1=Inactive')->default(0);
            $table->unsignedTinyInteger('private')->comment('0=Public,1=Private')->default(0);
            $table->string('name_abbreviation')->nullable();
            $table->string('logo_image')->nullable();
            $table->unsignedSmallInteger('payment_method')->default(Office::PAYMENT_METHOD_DEFAULT);
            $table->index('name');
            $table->index('primary_contact');
            $table->index('website');
            $table->index('inactive');
            $table->index('private');
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
        Schema::dropIfExists('offices');
    }
}
