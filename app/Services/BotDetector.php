<?php

namespace App\Services;

class BotDetector
{
    protected static array $botSignatures = [
        'bot',
        'crawl',
        'spider',
        'slurp',
        'google',
        'bing',
        'yandex',
        'baidu',
        'duckduck',
        'yahoo',
        'semrush',
        'ahrefs',
        'mj12',
        'dotbot',
        'petalbot',
        'bytespider',
        'curl',
        'wget',
        'python',
        'guzzle',
        'postman',
        'httpclient',
        'facebookexternalhit',
        'whatsapp',
        'twitterbot',
        'telegram',
        'pinterest',
        'linkedin',
        'headlesschrome',
        'phantomjs',
        'selenium',
        'puppeteer',
        'lighthouse',
        'screaming frog',
        'node-fetch',
        'axios',
        'go-http-client',
        'java',
    ];

    public static function isBot(?string $userAgent): bool
    {
        if ($userAgent === null || trim($userAgent) === '') {
            return true;
        }

        $escaped = array_map(fn ($sig) => preg_quote($sig, '#'), self::$botSignatures);
        $pattern = '#'.implode('|', $escaped).'#i';

        return (bool) preg_match($pattern, $userAgent);
    }
}
