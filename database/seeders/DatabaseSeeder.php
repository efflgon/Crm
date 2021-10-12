<?php

namespace Database\Seeders;

use App\Models\Integration;
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
        User::create([
            'name' => 'admin',
            'password' => bcrypt('20041998'),
            'email' => 'efflgon@gmail.com'
        ]);

        Integration::create([
            'name' => 'ClickMate',
            'handler_class' => 'App\Integrations\ClickMate',
        ]);

        Integration::create([
            'name' => 'Avix',
            'handler_class' => 'App\Integrations\Avix',
        ]);

        Integration::create([
            'name' => 'DrCash',
            'handler_class' => 'App\Integrations\DrCash',
        ]);
    }
}
