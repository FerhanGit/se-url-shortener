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

    public function generetaToken(): string
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
}