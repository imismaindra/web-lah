<?php

namespace Database\Seeders;

use App\Models\Topik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopikSeeder extends Seeder
{
    public function run(): void
    {
        $topiks = [
            ['nama' => 'Mesir Kuno', 'deskripsi' => 'Firaun, piramida, dan peradaban Sungai Nil.', 'urutan' => 1],
            ['nama' => 'Yunani Kuno', 'deskripsi' => 'Polis, filsafat, dan demokrasi Athena.', 'urutan' => 2],
            ['nama' => 'Romawi', 'deskripsi' => 'Republik, Kekaisaran, dan warisan hukum Romawi.', 'urutan' => 3],
            ['nama' => 'Peradaban China', 'deskripsi' => 'Dinasti-dinasti dan inovasi peradaban Tiongkok.', 'urutan' => 4],
            ['nama' => 'India Kuno', 'deskripsi' => 'Peradaban lembah sungai dan kerajaan-kerajaan India.', 'urutan' => 5],
            ['nama' => 'Kekaisaran Mongol', 'deskripsi' => 'Ekspansi Genghis Khan dan kekaisaran terluas sepanjang sejarah.', 'urutan' => 6],
            ['nama' => 'Kesultanan Utsmaniyah', 'deskripsi' => 'Kekaisaran yang menghubungkan Timur dan Barat.', 'urutan' => 7],
            ['nama' => 'Perang Salib', 'deskripsi' => 'Perang-perang agama di kawasan Timur Tengah.', 'urutan' => 8],
            ['nama' => 'Renaisans', 'deskripsi' => 'Kelahiran kembali seni dan sains di Eropa.', 'urutan' => 9],
            ['nama' => 'Revolusi Industri', 'deskripsi' => 'Perubahan besar dari agraris menuju industri.', 'urutan' => 10],
            ['nama' => 'Perang Dunia I', 'deskripsi' => 'Konflik global 1914–1918.', 'urutan' => 11],
            ['nama' => 'Perang Dunia II', 'deskripsi' => 'Konflik global 1939–1945.', 'urutan' => 12],
            ['nama' => 'Perang Dingin', 'deskripsi' => 'Persaingan blok Barat dan Timur 1947–1991.', 'urutan' => 13],
            ['nama' => 'Kerajaan Nusantara', 'deskripsi' => 'Sriwijaya, Majapahit, dan kerajaan-kerajaan di Nusantara.', 'urutan' => 14],
            ['nama' => 'Kemerdekaan Indonesia', 'deskripsi' => 'Perjuangan menuju proklamasi dan pengakuan kedaulatan.', 'urutan' => 15],
        ];

        foreach ($topiks as $topik) {
            Topik::firstOrCreate(
                ['nama' => $topik['nama']],
                array_merge($topik, ['slug' => Str::slug($topik['nama'])])
            );
        }
    }
}
