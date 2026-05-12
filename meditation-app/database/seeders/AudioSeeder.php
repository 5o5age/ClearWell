<?php

namespace Database\Seeders;

use App\Models\Audio;
use App\Models\User;
use Illuminate\Database\Seeder;

class AudioSeeder extends Seeder
{
    private const SAMPLE_RATE = 22050;

    public function run(): void
    {
        $admin = User::where('email', 'admin@clearwell.test')->first();

        if (! $admin) {
            return;
        }

        $dir = storage_path('app/public/audios');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $tracks = [
            [
                'slug'        => 'ocean-breath',
                'title'       => 'Ocean Breath',
                'description' => 'Slow surf in 12-second waves. Good background for breathwork.',
                'generator'   => 'genOceanBreath',
                'seconds'     => 32,
                'channels'    => 1,
            ],
            [
                'slug'        => 'forest-drone',
                'title'       => 'Forest Drone',
                'description' => 'Low sustained tones in A minor, drifting against each other.',
                'generator'   => 'genForestDrone',
                'seconds'     => 32,
                'channels'    => 1,
            ],
            [
                'slug'        => 'rain-on-stone',
                'title'       => 'Rain on Stone',
                'description' => 'Filtered rain with the occasional clearer drop. Works for focused reading.',
                'generator'   => 'genRainOnStone',
                'seconds'     => 32,
                'channels'    => 1,
            ],
            [
                'slug'        => 'temple-bell',
                'title'       => 'Temple Bell',
                'description' => 'A bell struck every 8 seconds. Useful as a meditation anchor.',
                'generator'   => 'genTempleBell',
                'seconds'     => 40,
                'channels'    => 1,
            ],
            [
                'slug'        => 'deep-hum',
                'title'       => 'Deep Hum (Binaural)',
                'description' => 'Binaural drone (100 Hz / 107 Hz) producing a 7 Hz beat. Use headphones.',
                'generator'   => 'genDeepHum',
                'seconds'     => 36,
                'channels'    => 2,
            ],
        ];

        foreach ($tracks as $track) {
            $relative = 'audios/' . $track['slug'] . '.wav';
            $absolute = $dir . '/' . $track['slug'] . '.wav';

            if (! file_exists($absolute)) {
                $pcm = $this->{$track['generator']}(self::SAMPLE_RATE, $track['seconds']);
                $this->writeWav($absolute, $pcm, self::SAMPLE_RATE, $track['channels']);
            }

            Audio::updateOrCreate(
                ['title' => $track['title']],
                [
                    'user_id'     => $admin->id,
                    'description' => $track['description'],
                    'file_path'   => $relative,
                ]
            );
        }
    }

    private function writeWav(string $path, string $pcm, int $sampleRate, int $channels): void
    {
        $bitsPerSample = 16;
        $byteRate      = $sampleRate * $channels * $bitsPerSample / 8;
        $blockAlign    = $channels * $bitsPerSample / 8;
        $dataSize      = strlen($pcm);
        $chunkSize     = 36 + $dataSize;

        $fh = fopen($path, 'wb');
        fwrite($fh, 'RIFF');
        fwrite($fh, pack('V', $chunkSize));
        fwrite($fh, 'WAVE');
        fwrite($fh, 'fmt ');
        fwrite($fh, pack('V', 16));
        fwrite($fh, pack('v', 1));
        fwrite($fh, pack('v', $channels));
        fwrite($fh, pack('V', $sampleRate));
        fwrite($fh, pack('V', (int) $byteRate));
        fwrite($fh, pack('v', (int) $blockAlign));
        fwrite($fh, pack('v', $bitsPerSample));
        fwrite($fh, 'data');
        fwrite($fh, pack('V', $dataSize));
        fwrite($fh, $pcm);
        fclose($fh);
    }

    private function f2b(float $s): string
    {
        $s = max(-1.0, min(1.0, $s));
        return pack('v', ((int) round($s * 32767)) & 0xFFFF);
    }

    private function fade(float $t, int $seconds): float
    {
        if ($t < 1.0) return $t;
        if ($t > $seconds - 1.0) return max(0.0, $seconds - $t);
        return 1.0;
    }

    private function genOceanBreath(int $sr, int $seconds): string
    {
        $frames = $sr * $seconds;
        $buf = '';
        $l1 = 0.0; $l2 = 0.0; $l3 = 0.0;

        for ($i = 0; $i < $frames; $i++) {
            $t = $i / $sr;
            $n = mt_rand() / mt_getrandmax() * 2 - 1;

            $l1 = $l1 * 0.985 + $n * 0.015;
            $l2 = $l2 * 0.94  + $l1 * 0.06;
            $l3 = $l3 * 0.82  + $l2 * 0.18;

            $am = 0.35 + 0.65 * (0.5 + 0.5 * sin(2 * M_PI * (1.0 / 12.0) * $t - M_PI / 2));

            $s = $l3 * 6.5 * $am * $this->fade($t, $seconds);
            $buf .= $this->f2b($s);
        }
        return $buf;
    }

    private function genForestDrone(int $sr, int $seconds): string
    {
        $frames = $sr * $seconds;
        $buf = '';

        $voices = [
            ['f' => 110.00, 'phase' => 0.0, 'amp' => 0.18, 'detuneAmp' => 0.30, 'detuneHz' => 0.05],
            ['f' => 130.81, 'phase' => 0.5, 'amp' => 0.14, 'detuneAmp' => 0.25, 'detuneHz' => 0.07],
            ['f' => 164.81, 'phase' => 1.3, 'amp' => 0.13, 'detuneAmp' => 0.35, 'detuneHz' => 0.04],
            ['f' => 220.00, 'phase' => 2.1, 'amp' => 0.10, 'detuneAmp' => 0.40, 'detuneHz' => 0.06],
            ['f' => 261.63, 'phase' => 3.0, 'amp' => 0.07, 'detuneAmp' => 0.45, 'detuneHz' => 0.09],
        ];

        $dt = 1.0 / $sr;
        foreach ($voices as $k => $v) { $voices[$k]['acc'] = $v['phase']; }

        for ($i = 0; $i < $frames; $i++) {
            $t = $i / $sr;
            $s = 0.0;
            foreach ($voices as $k => &$v) {
                $detune = sin(2 * M_PI * $v['detuneHz'] * $t + $v['phase']) * $v['detuneAmp'];
                $v['acc'] += 2 * M_PI * ($v['f'] + $detune) * $dt;
                $s += sin($v['acc']) * $v['amp'];
            }
            unset($v);

            $am = 0.72 + 0.28 * sin(2 * M_PI * 0.07 * $t);
            $s *= $am * $this->fade($t, $seconds);
            $buf .= $this->f2b($s);
        }
        return $buf;
    }

    private function genRainOnStone(int $sr, int $seconds): string
    {
        $frames = $sr * $seconds;
        $buf = '';
        $l1 = 0.0; $l2 = 0.0;

        $dropFrames = (int) (0.06 * $sr);
        $drop = [];
        for ($j = 0; $j < $dropFrames; $j++) {
            $tt = $j / $sr;
            $env = exp(-$tt * 55.0);
            $tone = sin(2 * M_PI * 2400 * $tt) * 0.5
                  + sin(2 * M_PI * 3600 * $tt) * 0.3
                  + (mt_rand() / mt_getrandmax() * 2 - 1) * 0.25;
            $drop[$j] = $tone * $env;
        }

        $dropsByFrame = [];
        $next = 0;
        while ($next < $frames) {
            $dropsByFrame[$next] = 0.08 + (mt_rand() / mt_getrandmax()) * 0.18;
            $next += (int) (($sr / 8) * (0.4 + mt_rand() / mt_getrandmax() * 1.8));
        }

        $activeDrops = [];

        for ($i = 0; $i < $frames; $i++) {
            $t = $i / $sr;
            $n = mt_rand() / mt_getrandmax() * 2 - 1;

            $l1 = $l1 * 0.93 + $n * 0.07;
            $l2 = $l2 * 0.86 + $l1 * 0.14;
            $bed = $l2 * 2.8;

            if (isset($dropsByFrame[$i])) {
                $activeDrops[] = ['start' => $i, 'gain' => $dropsByFrame[$i]];
            }

            $dropSum = 0.0;
            foreach ($activeDrops as $k => $d) {
                $rel = $i - $d['start'];
                if ($rel >= $dropFrames) {
                    unset($activeDrops[$k]);
                    continue;
                }
                $dropSum += $drop[$rel] * $d['gain'];
            }

            $s = ($bed * 0.35) + $dropSum;
            $s *= $this->fade($t, $seconds);
            $buf .= $this->f2b($s);
        }
        return $buf;
    }

    private function genTempleBell(int $sr, int $seconds): string
    {
        $frames = $sr * $seconds;
        $buf = '';

        $strikePeriod = 8.0;
        $firstStrike  = 1.0;
        $fundamental  = 220.0;

        $partials = [
            ['ratio' => 1.00, 'amp' => 0.55, 'decay' => 1.6],
            ['ratio' => 2.00, 'amp' => 0.32, 'decay' => 2.4],
            ['ratio' => 2.40, 'amp' => 0.20, 'decay' => 3.2],
            ['ratio' => 3.00, 'amp' => 0.14, 'decay' => 4.0],
            ['ratio' => 4.20, 'amp' => 0.08, 'decay' => 5.0],
            ['ratio' => 5.40, 'amp' => 0.05, 'decay' => 6.0],
        ];

        for ($i = 0; $i < $frames; $i++) {
            $t = $i / $sr;
            $s = 0.0;

            $timeSince = $t - $firstStrike;
            if ($timeSince >= 0) {
                $strikeIndex = (int) floor($timeSince / $strikePeriod);
                $strikeTime  = $firstStrike + $strikeIndex * $strikePeriod;
                $rel         = $t - $strikeTime;
                if ($rel >= 0) {
                    foreach ($partials as $p) {
                        $env = exp(-$rel * $p['decay']);
                        $s += sin(2 * M_PI * $fundamental * $p['ratio'] * $rel) * $p['amp'] * $env;
                    }
                    if ($rel < 0.012) {
                        $s += (mt_rand() / mt_getrandmax() * 2 - 1) * 0.35 * (1 - $rel / 0.012);
                    }
                }
            }

            $s *= 0.75 * $this->fade($t, $seconds);
            $buf .= $this->f2b($s);
        }
        return $buf;
    }

    private function genDeepHum(int $sr, int $seconds): string
    {
        $frames = $sr * $seconds;
        $buf = '';
        $dt = 1.0 / $sr;

        $fL = 100.0;
        $fR = 107.0;
        $fU = 203.5;

        $accL = 0.0;
        $accR = 0.0;
        $accU = 0.0;

        for ($i = 0; $i < $frames; $i++) {
            $t = $i / $sr;

            $accL += 2 * M_PI * $fL * $dt;
            $accR += 2 * M_PI * $fR * $dt;
            $accU += 2 * M_PI * $fU * $dt;

            $am = 0.60 + 0.40 * sin(2 * M_PI * 0.06 * $t);
            $fade = $this->fade($t, $seconds);

            $l = (sin($accL) * 0.40 + sin($accU) * 0.08) * $am * $fade;
            $r = (sin($accR) * 0.40 + sin($accU) * 0.08) * $am * $fade;

            $buf .= $this->f2b($l) . $this->f2b($r);
        }
        return $buf;
    }
}
