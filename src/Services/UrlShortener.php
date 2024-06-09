<?php

namespace App\Services;

use App\Entity\UrlShortener as EntityUrlShortener;

final class UrlShortener
{
    public const SHORT_URL_LENGTH = 9;
    public const RANDOM_BYTES = 32;

    private EntityUrlShortener $urlShortenerEntity;

    public function __construct(EntityUrlShortener $urlShortenerEntity) {
        $this->urlShortenerEntity = $urlShortenerEntity;
    }

    public function generateToken(): string
    {
        $token = substr(
            base64_encode(
                sha1(
                    uniqid(
                        random_bytes(self::RANDOM_BYTES),
                        true
                    )
                )
            ),
            0,
            self::SHORT_URL_LENGTH
        );

        return $token;
    }

    public function checkIfUrlExists(string $url){
        $headers = @get_headers($url);
        if($headers === false) return false;
        
        return preg_grep('~^HTTP/\d+\.\d+\s+2\d{2}~',$headers) ? true : false;
      }
}