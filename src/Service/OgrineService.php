<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OgrineService
{
    private const OGRINE_URL = 'https://www.dofus.com/fr/achat-kamas/cours-kama-ogrines'; // TODO: Env var
    private const HEADERS = [
        'User-Agent' => 'Mozilla/5.0',
    ];

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetchLatestOgrineValue()
    {
        $response = $this->httpClient->request('GET', self::OGRINE_URL, [ 'headers' => self::HEADERS ]);

        $html = $response->getContent();

        $crawler = new Crawler($html);

        $crawler = $crawler->filterXPath('//script');
        foreach ($crawler as $domElement) {
            $content = trim($domElement->textContent);

            if (str_starts_with($content, 'RATES =')) {
                $jsObject = trim($content, 'RATES = ' . ';');

                $decodedObject = json_decode($jsObject, true);

                $reversedObject = array_reverse($decodedObject, true);

                $timestamps = array_keys($reversedObject);

                return [
                    'currentRateTimestamp' => substr($timestamps[1], 0, -3),
                    'currentRate' => $reversedObject[$timestamps[1]],
                    'previousRateTimestamp' => substr($timestamps[2], 0, -3),
                    'previousRate' => $reversedObject[$timestamps[2]],
                ];
            }
        }

        throw new \Exception('No <script> tag containing "RATES" was found');
    }
}
