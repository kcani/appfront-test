<?php

namespace App\Libs;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Intl\Currencies;

class ExchangeRateLib
{
    /**
     * Returns the exchange rate for the given currencies.
     *
     * @param string|null $from
     * @param string|null $to
     * @param bool $useCachedResult
     * @return array{value: float, from: string, from_symbol: string, to: string, to_symbol: string}
     */
    public static function get(string $from = null, string $to = null, bool $useCachedResult = true): array
    {
        $from = $from ?: Config::get('external.exchange-rate.base_from_currency');
        $to = $to ?: Config::get('external.exchange-rate.base_to_currency');

        $cacheKey = self::getCacheKey($from);

        $fromInUpperCase = strtoupper($from);
        $toInUpperCase = strtoupper($to);

        // If cached result will be used, check if the result is cached before and return its value.
        if ($useCachedResult && Cache::has($cacheKey)) {
            return self::getResult(Cache::get($cacheKey)[$fromInUpperCase], $fromInUpperCase, $toInUpperCase);
        }

        $failed = false;
        try {
            $baseUrl = Config::get('external.exchange-rate.base_url');
            $url = "{$baseUrl}/v6/latest/{$fromInUpperCase}";
            $client = new Client();
            $response = $client->request('GET', $url);
            // The shape of the response will be in this shape:
            // [
            //      'result' => 'success'.
            //      ...
            //      ...
            //      'rates' => [
            //          'EUR' => 0.8,
            //          'GBP' => 1.2,
            //          ...
            //      ]
            // ]
            $responseAssoc = json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException) {
            $responseAssoc = [];
            $failed = true;
        }

        $failed = (
            $failed ||
            !isset($responseAssoc['result']) ||
            $responseAssoc['result'] !== 'success' ||
            !isset($responseAssoc['rates'])
        );
        if ($failed) {
            throw new \RuntimeException('Failed to get data from third party.');
        }

        $exchangeRates = $responseAssoc['rates'];

        // Cache the result for a short time, because the exchange rate changes very often.
        Cache::put($cacheKey, $exchangeRates, now()->addMinutes(10));

        if (!isset($exchangeRates[$toInUpperCase])) {
            throw new \RuntimeException("Currency: {$toInUpperCase} is not supported.");
        }

        return self::getResult($exchangeRates[$toInUpperCase], $fromInUpperCase, $toInUpperCase);
    }

    /**
     * Get the response array shape.
     *
     * @param float $value
     * @param string $from
     * @param string $to
     * @return array{value: float, from: string, from_symbol: string, to: string, to_symbol: string}
     */
    private static function getResult(float $value, string $from, string $to): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        return [
            'value' => $value,
            'from' => $from,
            'from_symbol' => Currencies::getSymbol($from),
            'to' => $to,
            'to_symbol' => Currencies::getSymbol($to)
        ];
    }

    /**
     * Returns the generated cache key for storing the exchange rate result.
     *
     * @param string $from
     * @return string
     */
    private static function getCacheKey(string $from): string
    {
        return "ExchangeRateLib_from_{$from}";
    }
}
