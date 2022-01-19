<?php

use App\Models\Service;
use App\Models\ServiceSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedDouble("install", 10, 2)->default(0);
            $table->unsignedDouble("repair", 10, 2)->default(0);
            $table->unsignedDouble("removal", 10, 2)->default(0);
            $table->unsignedDouble("delivery", 10, 2)->default(0);
            $table->unsignedDouble('rush_order', 10, 2)->default(0);
            $table->timestamps();
        });

        ServiceSetting::create(ServiceSetting::DEFAULT_SETTING);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_settings');
    }
}
