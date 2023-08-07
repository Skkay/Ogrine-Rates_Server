<?php

namespace App\Controller;

use App\DTO\DiscordWebhookDTO;
use App\Entity\DiscordWebhook;
use App\Repository\DiscordWebhookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class DiscordNotificationController extends AbstractController
{
    private DiscordWebhookRepository $discordWebhookRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->discordWebhookRepository = $registry->getRepository(DiscordWebhook::class);
    }

    #[Route('/discord-notification', name: 'app:discord_notification.add_or_remove', methods: ['POST'])]
    public function addOrRemove(#[MapRequestPayload] DiscordWebhookDTO $discordWebhookDTO): JsonResponse
    {
        $discordWebhook = $this->discordWebhookRepository->findOneBy(['url' => $discordWebhookDTO->url]);

        if ($discordWebhook === null) {
            $discordWebhook = (new DiscordWebhook())
                ->setUrl($discordWebhookDTO->url)
                ->setMessageLocale($discordWebhookDTO->messageLocale)
            ;

            $this->discordWebhookRepository->save($discordWebhook, true);

            return $this->json([
                'status' => 201,
                'detail' => 'Discord webhook URL successfully added',
            ], 201);
        }

        $this->discordWebhookRepository->remove($discordWebhook, true);

        return $this->json([
            'status' => 200,
            'detail' => 'Discord webhook URL successfully removed',
        ]);
    }
}
