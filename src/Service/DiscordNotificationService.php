<?php

namespace App\Service;

use App\DataObject\FetchedOgrineValues;
use App\Entity\DiscordWebhook;
use App\Repository\DiscordWebhookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DiscordNotificationService
{
    private ObjectManager $om;
    private DiscordWebhookRepository $discordWebhookRepository;
    private HttpClientInterface $httpClient;
    private TranslatorInterface $translator;

    public function __construct(ManagerRegistry $registry, HttpClientInterface $httpClient, TranslatorInterface $translator)
    {
        $this->om = $registry->getManager();
        $this->discordWebhookRepository = $registry->getRepository(DiscordWebhook::class);
        $this->httpClient = $httpClient;
        $this->translator = $translator;
    }

    public function sendToAll(FetchedOgrineValues $fetchedOgrineValues)
    {
        $webhooks = $this->discordWebhookRepository->findAll();

        $currentRate = $fetchedOgrineValues->getCurrentRate();
        $currentRateDateTime = (new \DateTime())->setTimestamp($fetchedOgrineValues->getCurrentRateTimestamp());
        $rateChange = $currentRate - $fetchedOgrineValues->getPreviousRate();
        $rateChangePercent = round($rateChange / $fetchedOgrineValues->getPreviousRate() * 100, 2);

        $responses = [];
        foreach ($webhooks as $webhook) {
            $response = $this->send($webhook, [
                'current_rate' => $currentRate,
                'current_rate_datetime' => $currentRateDateTime,
                'rate_change' => $rateChange,
                'rate_change_percent' => $rateChangePercent,
            ]);

            $responses[] = $response;

            $webhook
                ->setLastResponseStatus($response['discord_response_status_code'])
                ->setLastResponse($response['discord_response'])
            ;

            if ($response['discord_response_status_code'] === 204) {
                $webhook->setDatetimeLastSuccessfulResponse(new \DateTime());
            }

            $this->om->persist($webhook);
        }

        $this->om->flush();

        return $responses;
    }

    public function send(DiscordWebhook $discordWebhook, array $data)
    {
        $message = $this->prepareMessage($data, $discordWebhook->getMessageLocale());

        $response = $this->httpClient->request('POST', $discordWebhook->getUrl(), [
            'json' => [
                'content' => $message,
            ],
        ]);

        return [
            'webhook_url' => $discordWebhook->getUrl(),
            'discord_response_status_code' => $response->getStatusCode(),
            'discord_response' => $response->getContent(false),
        ];
    }

    private function prepareMessage(array $data, string $locale): string
    {
        $messageCurrentRate = $this->translator->trans('notification.discord.message.current_rate', locale: $locale, parameters: [
            'current_rate' => $data['current_rate'],
            'datetime' => $data['current_rate_datetime'],
        ]);

        $messageEvolution = $this->translator->trans('notification.discord.message.evolution', locale: $locale, parameters: [
            'is_positive' => $data['rate_change'] < 2 ? 'no' : 'yes',
            'rate_change' => $data['rate_change'],
            'rate_change_percent' => $data['rate_change_percent'],
        ]);

        return $messageCurrentRate . ' ' . $messageEvolution;
    }
}
