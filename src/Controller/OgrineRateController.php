<?php

namespace App\Controller;

use App\DTO\OgrineRatePaginationDTO;
use App\Entity\OgrineRate;
use App\Repository\OgrineRateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class OgrineRateController extends AbstractController
{
    private OgrineRateRepository $ogrineRateRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->ogrineRateRepository = $doctrine->getRepository(OgrineRate::class);
    }

    #[Route('/ogrineRates', name: 'app:ogrine_rate.get_all', methods: ['GET'])]
    public function getAll(#[MapQueryString] OgrineRatePaginationDTO $ogrineRatePaginationDTO = new OgrineRatePaginationDTO()): JsonResponse
    {
        $sort = $ogrineRatePaginationDTO->sort;
        $limit = $ogrineRatePaginationDTO->limit ?: null;

        $ogrineRates = $this->ogrineRateRepository->findBy([], ['datetime' => $sort], $limit);

        return $this->json([
            'status' => 200,
            'sort' => $sort,
            'limit' => $limit,
            'count' => count($ogrineRates),
            'rates' => $ogrineRates,
        ]);
    }
}
