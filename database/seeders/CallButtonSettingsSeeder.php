<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CallButtonSetting;

class CallButtonSettingsSeeder extends Seeder
{
    public function run()
    {
        // Create or update A, B, C
        CallButtonSetting::updateOrCreate(
            ['button_type' => 'A'],
            ['max_people' => 3]
        );
        CallButtonSetting::updateOrCreate(
            ['button_type' => 'B'],
            ['max_people' => 5]
        );
        CallButtonSetting::updateOrCreate(
            ['button_type' => 'C'],
            ['max_people' => 7]
        );
    }
}
