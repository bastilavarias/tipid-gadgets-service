<?php

namespace Database\Seeders;

use App\Models\ItemWarranty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemWarrantySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warranties = ['Personal Warranty', 'Shop Warranty', 'No Warranty'];
        foreach ($warranties as $warranty) {
            ItemWarranty::create([
                'name' => $warranty,
                'slug' => Str::of($warranty)->snake(),
            ]);
        }
    }
}
