<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const REMOVED_SLUGS = ['muzika', 'apzinatiba', 'pateiciba', 'joga'];

    public function up(): void
    {
        DB::table('tags')->whereIn('slug', self::REMOVED_SLUGS)->delete();
    }

    public function down(): void
    {
        $restore = [
            ['name' => 'Apzinātība', 'slug' => 'apzinatiba'],
            ['name' => 'Pateicība',  'slug' => 'pateiciba'],
            ['name' => 'Joga',       'slug' => 'joga'],
            ['name' => 'Mūzika',     'slug' => 'muzika'],
        ];

        foreach ($restore as $tag) {
            DB::table('tags')->updateOrInsert(
                ['slug' => $tag['slug']],
                ['name' => $tag['name'], 'created_at' => now(), 'updated_at' => now()],
            );
        }
    }
};
