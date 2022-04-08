<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Cables/Adapters',
            'Casing/PSU',
            'CD/DVD R/RW',
            'Coolers/Fans',
            'Desktops/Pre-built',
            'DigiCams/WebCams',
            'Game Consoles',
            'Game Controllers',
            'Games/Software',
            'Graphics Cards',
            'Hard Drives',
            'IO/Add-on Cards',
            'IT Books/References',
            'Laptops/PDAs',
            'Laptops/PDA - Accessories/Parts',
            'Media Players',
            'Memory Modules',
            'Mice/Keyboards',
            'Monitor CRT/LCD',
            'Motherboards',
            'Multiple Items',
            'Network/NET Devices',
            'Portable Drives',
            'Portable Media',
            'Processors',
            'Printers/Scanners',
            'Printer Inks/CIS/Toners',
            'Repair Services - PC/Laptop',
            'Sound Cards',
            'Speakers/Headsets/Microphones',
            'Tech/IT Services',
            'UPSes/AVRs',
            'Other PC Devices',
            'Smartphones',
        ];
        foreach ($categories as $category) {
            ItemCategory::create([
                'name' => $category,
                'slug' => Str::of($category)->snake(),
            ]);
        }
    }
}
