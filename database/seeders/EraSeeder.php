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
            ['nama' => 'Praaksara', 'periode' => 'Sebelum abad ke-4 M', 'urutan' => 1],
            ['nama' => 'Klasik Hindu-Buddha', 'periode' => 'Abad ke-4 – ke-15 M', 'urutan' => 2],
            ['nama' => 'Penyebaran Islam', 'periode' => 'Abad ke-13 – ke-16 M', 'urutan' => 3],
            ['nama' => 'Kejayaan Kesultanan', 'periode' => 'Abad ke-16 – ke-17 M', 'urutan' => 4],
            ['nama' => 'Kolonialisme Barat', 'periode' => 'Abad ke-16 – 1945', 'urutan' => 5],
            ['nama' => 'Pergerakan Nasional', 'periode' => '1908 – 1945', 'urutan' => 6],
            ['nama' => 'Revolusi & Kemerdekaan', 'periode' => '1945 – 1949', 'urutan' => 7],
            ['nama' => 'Era Orde Lama', 'periode' => '1950 – 1966', 'urutan' => 8],
            ['nama' => 'Era Orde Baru', 'periode' => '1966 – 1998', 'urutan' => 9],
            ['nama' => 'Era Reformasi', 'periode' => '1998 – Sekarang', 'urutan' => 10],
        ];

        foreach ($eras as $era) {
            Era::firstOrCreate(
                ['nama' => $era['nama']],
                array_merge($era, ['slug' => Str::slug($era['nama'])])
            );
        }
    }
}
