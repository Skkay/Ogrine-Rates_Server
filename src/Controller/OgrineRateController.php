<?php

namespace App\Controller;

use App\Entity\OgrineRate;
use App\Repository\OgrineRateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class OgrineRateController extends AbstractController
{
    private OgrineRateRepository $ogrineRateRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->ogrineRateRepository = $doctrine->getRepository(OgrineRate::class);
    }

    #[Route('/ogrineRates', name: 'app:ogrine_rate.get_all')]
    public function getAll(): JsonResponse
    {
        $ogrineRates = $this->ogrineRateRepository->findAll();

        return $this->json([
            'rates' => $ogrineRates,
        ]);
    }
}
