<?php

namespace App\Controller;

use App\Entity\OgrineRate;
use App\Repository\OgrineRateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OgrineRateController extends AbstractController
{
    private OgrineRateRepository $ogrineRateRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->ogrineRateRepository = $doctrine->getRepository(OgrineRate::class);
    }

    #[Route('/ogrineRates', name: 'app:ogrine_rate.get_all')]
    public function getAll(Request $request): JsonResponse
    {
        $sort = strtoupper($request->query->get('sort', 'DESC')) === 'DESC' ? 'DESC' : 'ASC';
        $limit = $request->query->getInt('limit', 0) ?: null;

        $ogrineRates = $this->ogrineRateRepository->findBy([], ['datetime' => $sort], $limit);

        return $this->json([
            'sort' => $sort,
            'limit' => $limit,
            'count' => count($ogrineRates),
            'rates' => $ogrineRates,
        ]);
    }
}
