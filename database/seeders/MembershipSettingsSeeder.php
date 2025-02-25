<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipSetting;

class MembershipSettingsSeeder extends Seeder
{
    public function run()
    {
        // Create or update a record for membership settings
        // Adjust the values as needed
        MembershipSetting::updateOrCreate(
            [
                // If you only keep one row, you can use a key like 'id' => 1
                'id' => 1
            ],
            [
                'platinum_visits' => 200, // Number of visits for platinum
                'gold_visits'     => 100, // Number of visits for gold
                'silver_visits'   => 50,  // Number of visits for silver
            ]
        );
    }
}
