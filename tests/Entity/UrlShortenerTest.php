<?php declare(strict_types=1);

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\UrlShortener;
use DateTimeImmutable;

final class UrlShortenerTest extends TestCase
{
    public function testCanCreateUrlShortenerEntity(): void
    {
        $urlShortenertEntity = new UrlShortener();

        $urlShortenertEntity->setLongUrl('https://google.com/something_very_long');
        $urlShortenertEntity->setShortUrl('https://my_domain.com/shortUrl');
        $currentTime = new DateTimeImmutable();
        $urlShortenertEntity->setCreatedAt($currentTime);

        $this->assertSame('https://google.com/something_very_long', $urlShortenertEntity->getLongUrl());
        $this->assertSame('https://my_domain.com/shortUrl', $urlShortenertEntity->getShortUrl());
        $this->assertSame($currentTime, $urlShortenertEntity->getCreatedAt());
    }

    public function testCreatedAtValid(): void
    {
        $urlShortenertEntity = new UrlShortener();

        $urlShortenertEntity->setLongUrl('https://google.com/something_very_long');
        $urlShortenertEntity->setShortUrl('https://my_domain.com/shortUrl');
        $currentTime = new DateTimeImmutable();
        $urlShortenertEntity->setCreatedAt($currentTime); // we set the created time in the past

        $validTime = $_ENV['SHORT_URL_VALID_TIME'];

        $this->assertTrue(($urlShortenertEntity->getCreatedAt()->getTimeStamp() >= (time() - $validTime)));
    }

    public function testCreatedAtNotValid(): void
    {
        $urlShortenertEntity = new UrlShortener();

        $urlShortenertEntity->setLongUrl('https://google.com/something_very_long');
        $urlShortenertEntity->setShortUrl('https://my_domain.com/shortUrl');
        $pastTime = new DateTimeImmutable('-2 week');
        $urlShortenertEntity->setCreatedAt($pastTime); // we set the created time in the past

        $validTime = $_ENV['SHORT_URL_VALID_TIME'];
        
        $this->assertTrue(($urlShortenertEntity->getCreatedAt()->getTimeStamp() <= (time() - $validTime)));
    }
}