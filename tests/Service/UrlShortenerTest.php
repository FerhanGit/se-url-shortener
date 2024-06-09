<?php declare(strict_types=1);

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Services\UrlShortener;
use App\Entity\UrlShortener as UrlShortenerEntity;
use DateTimeImmutable;

final class UrlShortenerServiceTest extends TestCase
{
    public function testCanGenerateCorrectTokenForShortUrl(): void
    {
        $urlShortenertEntity = new UrlShortenerEntity();
        $urlShortenertEntity->setLongUrl('https://google.com/something_very_long');
        $urlShortenertEntity->setShortUrl('https://my_domain.com/shortUrl');
        $currentTime = new DateTimeImmutable();
        $urlShortenertEntity->setCreatedAt($currentTime);
    
        $urlShortenertService = new UrlShortener($urlShortenertEntity);

        $this->assertIsstring($urlShortenertService->generateToken());
        $this->assertEquals(UrlShortener::SHORT_URL_LENGTH, strlen($urlShortenertService->generateToken()));
    }
}