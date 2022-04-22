<?php

namespace Database\Seeders;

use App\Models\TopicSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            'Tech Help/Troubleshooting',
            'PC Hardware/Devices',
            'Programming/R & D',
            'Software/Games',
            'Modding/Overclocking',
            'Networking/Web',
            'Mobile Computing',
            'Digital Media & Artwork',
            'The Lounge',
        ];
        foreach ($sections as $section) {
            TopicSection::create([
                'name' => $section,
                'slug' => Str::of($section)->snake(),
            ]);
        }
    }
}
