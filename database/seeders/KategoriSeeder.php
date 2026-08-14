<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama' => 'Politik & Pemerintahan', 'deskripsi' => 'Peristiwa politik, pemerintahan, dan kebijakan negara.'],
            ['nama' => 'Perang & Militer', 'deskripsi' => 'Peperangan, strategi militer, dan konflik bersenjata.'],
            ['nama' => 'Ekonomi & Perdagangan', 'deskripsi' => 'Perkembangan ekonomi, perdagangan, dan sistem keuangan.'],
            ['nama' => 'Budaya & Seni', 'deskripsi' => 'Seni, sastra, adat istiadat, dan perkembangan budaya.'],
            ['nama' => 'Sains & Teknologi', 'deskripsi' => 'Penemuan ilmiah, inovasi teknologi, dan dampaknya.'],
            ['nama' => 'Agama & Kepercayaan', 'deskripsi' => 'Sejarah agama, kepercayaan, dan peranannya di masyarakat.'],
            ['nama' => 'Tokoh & Biografi', 'deskripsi' => 'Kisah hidup tokoh-tokoh penting dalam sejarah.'],
            ['nama' => 'Arkeologi & Warisan', 'deskripsi' => 'Penemuan arkeologis, situs, dan warisan budaya.'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['nama' => $kategori['nama']],
                array_merge($kategori, ['slug' => Str::slug($kategori['nama'])])
            );
        }
    }
}
