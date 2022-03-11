<?php

namespace App\Domain\Shared;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private const BASE_URL = "http://api.weatherstack.com/current";
    private const CACHE_KEY = "weather_location";

    public function __construct(
        private HttpClientInterface $client,
        private CacheItemPoolInterface $cache,
        private string $apiKey,
    )
    {
        
    }

    public function getWeather(string $location)
    {
        $weatherItem = $this->cache->getItem(self::CACHE_KEY);
        

        // Item does not exist in cache.
        if(!$weatherItem->isHit()){
            $data = $this->client->request(
                'GET',
                sprintf("%s?access_key=%s&query=%s", self::BASE_URL, $this->apiKey, $location)
            )->toArray();
            dump($data);
            $weatherItem->set(serialize("COUCOU"));
            $weatherItem->expiresAfter(3600 * 6); // Refresh each 6 hours.
            $this->cache->save($weatherItem); // Save
        }
        else{
            $weather = $weatherItem->get();
        }

        return $weather; // Return the actual value.
    }
}