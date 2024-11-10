<?php

namespace App\Repository;

use App\DataObject\FetchedRealTimeOgrineValue;
use App\Entity\RealTimeOgrineRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RealTimeOgrineRate>
 *
 * @method RealTimeOgrineRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method RealTimeOgrineRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method RealTimeOgrineRate[]    findAll()
 * @method RealTimeOgrineRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealTimeOgrineRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealTimeOgrineRate::class);
    }

    public function insert(FetchedRealTimeOgrineValue $fetchedRealTimeOgrineValue): void
    {
        $realTimeOgrineRate = (new RealTimeOgrineRate())
            ->setDatetime($fetchedRealTimeOgrineValue->getFetchedAt())
            ->setRate($fetchedRealTimeOgrineValue->getCurrentRate())
            ->setNumberOfOgrines($fetchedRealTimeOgrineValue->getNumberOfOgrines())
        ;

        $this->getEntityManager()->persist($realTimeOgrineRate);
        $this->getEntityManager()->flush();
    }
}
