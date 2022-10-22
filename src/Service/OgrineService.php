<?php

namespace App\Service;

use App\DataObject\FetchedOgrineValues;
use App\Entity\OgrineRate;
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

    private ObjectManager $om;
    private HttpClientInterface $httpClient;

    public function __construct(ManagerRegistry $doctrine, HttpClientInterface $httpClient)
    {
        $this->om = $doctrine->getManager();
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

                return new FetchedOgrineValues(
                    (int) substr($timestamps[1], 0, -3),
                    (float) $reversedObject[$timestamps[1]],
                    (int) substr($timestamps[2], 0, -3),
                    (float) $reversedObject[$timestamps[2]]
                );
            }
        }

        throw new \Exception('No <script> tag containing "RATES" was found');
    }

    public function insertLatestOgrineValue(FetchedOgrineValues $fetchedOgrine)
    {
        $ogrineRate = (new OgrineRate())
            ->setRateTenth($fetchedOgrine->getCurrentRate() * 10)
            ->setDatetime((new \DateTime())->setTimestamp($fetchedOgrine->getCurrentRateTimestamp()))
        ;

        $this->om->persist($ogrineRate);
        $this->om->flush();

        return $ogrineRate;
    }
}
