<?php

namespace Database\Seeders;

use App\Models\ItemSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = ['Item for Sale', 'Want to Buy'];
        foreach ($sections as $section) {
            ItemSection::create([
                'name' => $section,
                'slug' => Str::of($section)->snake(),
            ]);
        }
    }
}
