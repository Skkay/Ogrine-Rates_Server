<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DiscordWebhookDTO
{
    public function __construct(
        #[Assert\Regex('/^https:\/\/discord\.com\/api\/webhooks\/[0-9]+\/[a-zA-Z0-9_-]+$/')]
        public readonly string $url,

        #[Assert\Locale(canonicalize: true)]
        public readonly string $messageLocale
    ) {

    }
}
