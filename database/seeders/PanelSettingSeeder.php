<?php

namespace Database\Seeders;

use App\Models\PanelSetting;
use Illuminate\Database\Seeder;

class PanelSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PanelSetting::create(PanelSetting::DEFAULT_SETTING);
    }
}
