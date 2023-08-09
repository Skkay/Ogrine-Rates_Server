<?php

namespace App\Repository;

use App\Entity\DiscordWebhook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscordWebhook>
 *
 * @method DiscordWebhook|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscordWebhook|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscordWebhook[]    findAll()
 * @method DiscordWebhook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscordWebhookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscordWebhook::class);
    }

    public function save(DiscordWebhook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiscordWebhook $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
