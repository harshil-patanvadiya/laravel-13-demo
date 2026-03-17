<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'name' => 'Tag 1',
        ]);

        Tag::create([
            'name' => 'Tag 2',
        ]);

        Tag::create([
            'name' => 'Tag 3',
        ]);

        Tag::create([
            'name' => 'Tag 4',
        ]);

        Tag::create([
            'name' => 'Tag 5',
        ]);
    }
}
