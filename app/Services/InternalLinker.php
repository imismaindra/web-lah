<?php

namespace App\Services;

use App\Models\Artikel;

class InternalLinker
{
    /**
     * @var array<string, string>
     */
    protected static array $keywordMap = [
        'Perjanjian Giyanti 1755' => 'perjanjian-giyanti-1755-sejarah-lengkap-pecahnya-kerajaan-mataram-islam',
        'Perjanjian Giyanti' => 'perjanjian-giyanti-1755-sejarah-lengkap-pecahnya-kerajaan-mataram-islam',
        'jaringan maritim Majapahit' => 'menelusuri-jaringan-maritim-majapahit-pusat-perdagangan-rempah-nusantara-arsip-1877',
        'maritim Majapahit' => 'menelusuri-jaringan-maritim-majapahit-pusat-perdagangan-rempah-nusantara-arsip-1877',
        'kerajaan Majapahit' => 'menelusuri-jaringan-maritim-majapahit-pusat-perdagangan-rempah-nusantara-arsip-1877',
        'Majapahit' => 'menelusuri-jaringan-maritim-majapahit-pusat-perdagangan-rempah-nusantara-arsip-1877',
        'Kekaisaran Romawi Barat' => 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi',
        'Kekaisaran Romawi' => 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi',
        'Romawi Kuno' => 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi',
        'kejatuhan Romawi' => 'mengapa-kekaisaran-romawi-runtuh-bukan-sekadar-karena-invasi',
        'Karl Dönitz' => 'pemimpin-terakhir-nazi-jerman-mengapa-hitler-memilih-karl-donitz-saat-semuanya-sudah-berakhir',
        'Karl Donitz' => 'pemimpin-terakhir-nazi-jerman-mengapa-hitler-memilih-karl-donitz-saat-semuanya-sudah-berakhir',
        'ideologi fasis' => 'seberapa-bahayanya-ideologi-fasis-sejarah-dan-dampaknya',
        'fasisme' => 'seberapa-bahayanya-ideologi-fasis-sejarah-dan-dampaknya',
        'penjajah masuk Indonesia' => 'penjajah-masuk-indonesia-siapa-duluan-lewat-mana-dan-ngapain',
        'penjajahan Belanda' => 'penjajah-masuk-indonesia-siapa-duluan-lewat-mana-dan-ngapain',
        'bangsa Eropa ke Nusantara' => 'penjajah-masuk-indonesia-siapa-duluan-lewat-mana-dan-ngapain',
        'kedatangan bangsa Eropa' => 'penjajah-masuk-indonesia-siapa-duluan-lewat-mana-dan-ngapain',
        'Indonesia menjadi negara komunis' => 'bagaimana-jika-indonesia-menjadi-negara-komunis',
        'negara komunis' => 'bagaimana-jika-indonesia-menjadi-negara-komunis',
        'bom atom Hiroshima' => 'mengapa-hiroshima-dipilih-sebagai-target-bom-atom-pertama',
        'Hiroshima' => 'mengapa-hiroshima-dipilih-sebagai-target-bom-atom-pertama',
        'invasi Swiss' => 'mengapa-swiss-tidak-diserang-jerman-nazi',
        'netralitas Swiss' => 'mengapa-swiss-tidak-diserang-jerman-nazi',
        'evakuasi Dunkirk' => 'mengapa-jerman-membiarkan-pasukan-sekutu-melarikan-diri-dari-dunkirk',
        'Dunkirk' => 'mengapa-jerman-membiarkan-pasukan-sekutu-melarikan-diri-dari-dunkirk',
        'invasi Mongol ke Jawa' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
        'invasi Kekaisaran Mongol' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
        'Kekaisaran Mongol' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
        'pasukan Kubilai Khan' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
        'Kubilai Khan' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
        'Raden Wijaya' => 'mengapa-invasi-kekaisaran-mongol-gagal-di-jawa-siasat-raden-wijaya-melawan-pasukan-kubilai-khan',
    ];

    public static function link(string $html, ?Artikel $currentArticle = null, int $maxLinks = 4): string
    {
        if (empty(trim($html))) {
            return $html;
        }

        $usedTargets = [];
        if ($currentArticle && $currentArticle->slug) {
            $usedTargets[$currentArticle->slug] = true;
        }

        foreach (self::$keywordMap as $targetSlug) {
            if (str_contains($html, '/artikel/'.$targetSlug)) {
                $usedTargets[$targetSlug] = true;
            }
        }

        $candidates = [];
        foreach (self::$keywordMap as $keyword => $targetSlug) {
            if (! isset($usedTargets[$targetSlug])) {
                $candidates[] = [
                    'keyword' => $keyword,
                    'slug' => $targetSlug,
                ];
            }
        }

        if (empty($candidates)) {
            return $html;
        }

        usort($candidates, fn ($a, $b) => mb_strlen($b['keyword']) <=> mb_strlen($a['keyword']));

        $parts = preg_split('/(<[^>]+>)/u', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false) {
            return $html;
        }

        $forbiddenTags = ['a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'figcaption', 'script', 'style', 'button'];
        $forbiddenStack = [];
        $addedCount = 0;

        for ($i = 0; $i < count($parts); $i++) {
            $part = $parts[$i];

            if ($i % 2 === 1) {
                if (preg_match('/^<\s*(\/)?\s*([a-zA-Z0-9]+)/u', $part, $match)) {
                    $isClosing = ! empty($match[1]);
                    $tagName = strtolower($match[2]);

                    if (in_array($tagName, $forbiddenTags, true)) {
                        if ($isClosing) {
                            if (! empty($forbiddenStack) && end($forbiddenStack) === $tagName) {
                                array_pop($forbiddenStack);
                            }
                        } else {
                            $forbiddenStack[] = $tagName;
                        }
                    }
                }
            } else {
                if (empty($forbiddenStack) && $addedCount < $maxLinks) {
                    foreach ($candidates as $candidate) {
                        $slug = $candidate['slug'];
                        if (isset($usedTargets[$slug])) {
                            continue;
                        }

                        $kw = preg_quote($candidate['keyword'], '/');
                        $pattern = '/(?<!\p{L})('.$kw.')(?!\p{L})/ui';

                        if (preg_match($pattern, $part)) {
                            $url = url('/artikel/'.$slug);
                            $class = 'font-medium text-[#1e3a5f] underline decoration-[#1e3a5f]/40 underline-offset-4 transition hover:decoration-[#1e3a5f] dark:text-[#5b9bd5] dark:decoration-[#5b9bd5]/40 dark:hover:decoration-[#5b9bd5]';
                            $replacement = '<a href="'.$url.'" class="'.$class.'">$1</a>';

                            $part = preg_replace($pattern, $replacement, $part, 1);
                            $usedTargets[$slug] = true;
                            $addedCount++;

                            if ($addedCount >= $maxLinks) {
                                break;
                            }
                        }
                    }
                    $parts[$i] = $part;
                }
            }
        }

        return implode('', $parts);
    }
}
