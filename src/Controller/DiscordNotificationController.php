<?php

namespace App\Controller;

use App\Entity\DiscordWebhook;
use App\Exception\ApiException;
use App\Repository\DiscordWebhookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DiscordNotificationController extends AbstractController
{
    private DiscordWebhookRepository $discordWebhookRepository;

    public function __construct(ManagerRegistry $registry)
    {
        $this->discordWebhookRepository = $registry->getRepository(DiscordWebhook::class);
    }

    #[Route('/discord-notification', name: 'app:discord_notification.add_or_remove', methods: ['POST'])]
    public function addOrRemove(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null || !array_key_exists('url', $data)) {
            throw new ApiException(JsonResponse::HTTP_BAD_REQUEST, 'Invalid JSON');
        }

        $discordWebhook = $this->discordWebhookRepository->findOneBy(['url' => $data['url']]);

        if ($discordWebhook === null) {
            $discordWebhook = (new DiscordWebhook())
                ->setUrl($data['url'])
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
            'detail' => 'Dsicord webhook URL successfully removed',
        ]);
    }
}
