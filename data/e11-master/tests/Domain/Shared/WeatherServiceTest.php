<?php

namespace App\Tests\Domain\Shared;

use App\Domain\Shared\WeatherService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WeatherServiceTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        /** @var WeatherService $weatherService */
        $weatherService = static::getContainer()->get(WeatherService::class);

        dump($weatherService->getWeather("Angers"));
    }
}
