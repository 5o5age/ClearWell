<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@clearwell.test')->first();

        if (! $admin) {
            return;
        }

        $dir = storage_path('app/public/videos');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $videos = [
            [
                'slug'        => 'lena-ieelpa',
                'title'       => 'Lēna ieelpa',
                'description' => 'Maiga krāsu pulsācija 8 sekunžu ritmā — fons elpošanas praksei. Ļauj redzes laukam atrast vienu krāsu un sekot ritmam.',
                'tags'        => ['elposana', 'meditacija'],
                'source'      => 'color=c=0x3b5d7a:s=480x270:r=15:d=24',
                'filter'      => "hue=h='40*sin(2*PI*t/8)':s='1+0.35*sin(2*PI*t/8)',format=yuv420p",
            ],
            [
                'slug'        => 'meness-gaisma',
                'title'       => 'Mēness gaisma',
                'description' => 'Lēna zilgani violeta krāsu plūsma — vakara praksei vai aizmigšanai. Ļauj acīm mīksti atslābt.',
                'tags'        => ['miegs', 'meditacija'],
                'source'      => 'color=c=0x2a3e6a:s=480x270:r=15:d=24',
                'filter'      => "hue=h='60+30*sin(2*PI*t/15)':b='0.1*sin(2*PI*t/10)',format=yuv420p",
            ],
        ];

        $hasFfmpeg = $this->hasFfmpeg();

        foreach ($videos as $v) {
            $relative = 'videos/' . $v['slug'] . '.mp4';
            $absolute = $dir . '/' . $v['slug'] . '.mp4';

            if (! file_exists($absolute)) {
                if (! $hasFfmpeg) {
                    Log::warning("VideoSeeder: ffmpeg not available on PATH, skipping {$v['slug']}");
                    continue;
                }
                if (! $this->generate($absolute, $v['source'], $v['filter'])) {
                    continue;
                }
            }

            $video = Video::updateOrCreate(
                ['title' => $v['title']],
                [
                    'user_id'     => $admin->id,
                    'description' => $v['description'],
                    'file_path'   => $relative,
                ]
            );

            $tagIds = Tag::whereIn('slug', $v['tags'])->pluck('id')->all();
            $video->tags()->sync($tagIds);
        }
    }

    private function hasFfmpeg(): bool
    {
        $output = [];
        $code = 1;
        @exec('ffmpeg -version 2>&1', $output, $code);
        return $code === 0;
    }

    private function generate(string $path, string $source, string $filter): bool
    {
        $cmd = sprintf(
            'ffmpeg -y -f lavfi -i %s -vf %s -c:v libx264 -preset veryfast -crf 28 -movflags +faststart %s 2>&1',
            escapeshellarg($source),
            escapeshellarg($filter),
            escapeshellarg($path)
        );
        $output = [];
        $code = 1;
        exec($cmd, $output, $code);
        if ($code !== 0) {
            Log::error('VideoSeeder: ffmpeg failed', ['cmd' => $cmd, 'output' => $output]);
            return false;
        }
        return true;
    }
}
