<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Meditācija',  'slug' => 'meditacija'],
            ['name' => 'Elpošana',    'slug' => 'elposana'],
            ['name' => 'Miegs',       'slug' => 'miegs'],
            ['name' => 'Stress',      'slug' => 'stress'],
            ['name' => 'Daba',        'slug' => 'daba'],
            ['name' => 'Iesācējiem',  'slug' => 'iesacejiem'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => $tag['slug']], $tag);
        }
    }
}
