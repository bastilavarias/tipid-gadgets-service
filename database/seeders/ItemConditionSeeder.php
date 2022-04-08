<?php

namespace Database\Seeders;

use App\Models\ItemCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions = ['Brand New', 'Already Used', 'Newly Replaced', 'Defective'];
        foreach ($conditions as $condition) {
            ItemCondition::create([
                'name' => $condition,
                'slug' => Str::of($condition)->snake(),
            ]);
        }
    }
}
