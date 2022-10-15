<?php

namespace App\Repository;

use App\Entity\OgrineRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OgrineRate>
 *
 * @method OgrineRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method OgrineRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method OgrineRate[]    findAll()
 * @method OgrineRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OgrineRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OgrineRate::class);
    }

    public function save(OgrineRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OgrineRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
