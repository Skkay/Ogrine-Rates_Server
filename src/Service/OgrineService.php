<?php

namespace App\Service;

use App\DataObject\FetchedOgrineValues;
use App\Entity\OgrineRate;
use App\Repository\OgrineRateRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OgrineService
{
    private ObjectManager $om;
    private HttpClientInterface $httpClient;
    private ParameterBagInterface $params;
    private OgrineRateRepository $ogrineRateRepository;

    public function __construct(ManagerRegistry $doctrine, HttpClientInterface $httpClient, ParameterBagInterface $params, OgrineRateRepository $ogrineRateRepository)
    {
        $this->om = $doctrine->getManager();
        $this->httpClient = $httpClient;
        $this->params = $params;
        $this->ogrineRateRepository = $ogrineRateRepository;
    }

    public function fetchLatestOgrineValue()
    {
        $response = $this->httpClient->request('GET', $this->params->get('app.request.ogrine.fetch_url'), [
            'headers' => [
                'User-Agent' => $this->params->get('app.request.header.user_agent'),
            ]
        ]);

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
        return $this->insertOgrineValue(
            (new \DateTime())->setTimestamp($fetchedOgrine->getCurrentRateTimestamp()),
            $fetchedOgrine->getCurrentRate() * 10
        );
    }

    public function insertOgrineValue(\DateTimeInterface $datetime, int $rate)
    {
        $ogrineRate = (new OgrineRate())
            ->setRateTenth($rate)
            ->setDatetime($datetime)
        ;

        $this->om->persist($ogrineRate);
        $this->om->flush();

        return $ogrineRate;
    }

    public function deleteOgrineValue(\DateTimeInterface $datetime, int $rate)
    {
        $ogrineRate = $this->ogrineRateRepository->findOneBy([
            'datetime' => $datetime,
            'rateTenth' => $rate
        ]);

        if ($ogrineRate === null) {
            throw new EntityNotFoundException(sprintf('OgrineRate with datetime "%s" and rate "%s" not found', $datetime->format('c'), $rate));
        }

        $this->om->remove($ogrineRate);
        $this->om->flush();
    }
}
