<?php

namespace App\Service;

use App\DataObject\FetchedRealTimeOgrineValue;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RealTimeOgrineService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ParameterBagInterface $params,
    ) {
    }

    public function fetchRealTimeOgrineValue(): FetchedRealTimeOgrineValue
    {
        $response = $this->httpClient->request('GET', $this->params->get('app.request.ogrine.fetch_real_time_url'), [
            'headers' => [
                'User-Agent' => $this->params->get('app.request.header.user_agent'),
                'Cookie' => $this->params->get('app.request.header.cookie'),            ]
        ]);

        $html = $response->getContent();

        $crawler = new Crawler($html);

        $currentRate = $crawler
            ->filterXPath('//html/body/div[2]/div[2]/div/div/div/main/div[2]/table/tbody/tr[1]/td[3]/span[1]')
            ->innerText();

        $numberOfOgrines = $crawler
            ->filterXPath('//html/body/div[2]/div[2]/div/div/div/main/div[2]/table/tbody/tr[1]/td[2]')
            ->innerText();

        return new FetchedRealTimeOgrineValue(
            currentRate: (int) str_replace(' ', '', $currentRate),
            numberOfOgrines: (int) str_replace(' ', '', $numberOfOgrines),
        );
    }
}
