<?php

namespace Database\Seeders;

use App\Models\PanelSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Region::factory(10)->create();

        $users = [
            [
                'name' => "ibrahimSuperAdmin",
                'email' => "ibrahim@superadmin.com",
                'email_verified_at' => '2010-10-10',
                'password' => bcrypt('s'),
                'role' => 1,
                'first_name' => "ibrahim",
                'last_name' => "superadmin",
                'phone' => "0599665806",
                'address' => "",
                'city' => "khanyouns",
                'state' => "Gaza",
                'zipcode' => "0970"
            ]
        ];

        foreach ($users as $u) {
            User::create($u);
        }

        PanelSetting::create(PanelSetting::DEFAULT_SETTING);
    }
}
