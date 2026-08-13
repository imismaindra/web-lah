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
            ['nama' => 'Sejarah Politik', 'deskripsi' => 'Sejarah peristiwa politik, pemerintahan, dan kebijakan.', 'urutan' => 1],
            ['nama' => 'Sejarah Sosial & Budaya', 'deskripsi' => 'Perkembangan masyarakat, adat istiadat, seni, dan budaya.', 'urutan' => 2],
            ['nama' => 'Sejarah Ekonomi', 'deskripsi' => 'Perkembangan perekonomian, perdagangan, dan sistem ekonomi.', 'urutan' => 3],
            ['nama' => 'Sejarah Militer', 'deskripsi' => 'Perang, strategi militer, dan keamanan pertahanan.', 'urutan' => 4],
            ['nama' => 'Sejarah Agama', 'deskripsi' => 'Perkembangan agama, ajaran, dan peran agama dalam masyarakat.', 'urutan' => 5],
            ['nama' => 'Sejarah Sains & Teknologi', 'deskripsi' => 'Penemuan ilmiah, inovasi teknologi, dan dampaknya.', 'urutan' => 6],
            ['nama' => 'Biografi Tokoh', 'deskripsi' => 'Kisah hidup tokoh-tokoh penting dalam sejarah.', 'urutan' => 7],
            ['nama' => 'Arkeologi & Warisan', 'deskripsi' => 'Penemuan arkeologis, candi, situs, dan warisan budaya.', 'urutan' => 8],
        ];

        foreach ($topiks as $index => $topik) {
            Topik::firstOrCreate(
                ['nama' => $topik['nama']],
                array_merge($topik, ['slug' => Str::slug($topik['nama'])])
            );
        }
    }
}
