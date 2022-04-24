<?php

namespace Database\Seeders;

use App\Models\SearchType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SearchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Items for Sale', 'Want to Buys', 'Forum Topics', 'Members'];
        foreach ($types as $section) {
            SearchType::create([
                'name' => $section,
                'slug' => Str::of($section)->snake(),
            ]);
        }
    }
}
