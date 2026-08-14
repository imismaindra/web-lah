<?php

namespace Database\Seeders;

use App\Models\Era;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EraSeeder extends Seeder
{
    public function run(): void
    {
        $eras = [
            ['nama' => 'Praaksara', 'periode' => 'Sebelum 3500 SM', 'urutan' => 1],
            ['nama' => 'Peradaban Kuno', 'periode' => '3500 SM – 500 M', 'urutan' => 2],
            ['nama' => 'Abad Pertengahan', 'periode' => '500 – 1500 M', 'urutan' => 3],
            ['nama' => 'Zaman Modern Awal', 'periode' => '1500 – 1800 M', 'urutan' => 4],
            ['nama' => 'Zaman Modern', 'periode' => '1800 – 1945', 'urutan' => 5],
            ['nama' => 'Zaman Kontemporer', 'periode' => '1945 – Sekarang', 'urutan' => 6],
        ];

        foreach ($eras as $era) {
            Era::firstOrCreate(
                ['nama' => $era['nama']],
                array_merge($era, ['slug' => Str::slug($era['nama'])])
            );
        }
    }
}
