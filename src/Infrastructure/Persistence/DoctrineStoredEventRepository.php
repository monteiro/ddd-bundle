<?php

declare(strict_types=1);

namespace App\DDDBundle\Infrastructure\Persistence;

use App\DDDBundle\Domain\StoredEvent;
use App\DDDBundle\Domain\StoredEventRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|StoredEvent find($id, $lockMode = null, $lockVersion = null)
 * @method null|StoredEvent findOneBy(array $criteria, array $orderBy = null)
 * @method StoredEvent[]    findAll()
 * @method StoredEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineStoredEventRepository extends ServiceEntityRepository implements StoredEventRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoredEvent::class);
    }

    /**
     * @return array<StoredEvent>
     */
    public function nextUnpublishEvents(int $batchSize): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.published = false')
            ->orderBy('r.occurredOn', 'ASC')
            ->setMaxResults($batchSize)
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(StoredEvent $storedEvent): void
    {
        $this->getEntityManager()->persist($storedEvent);
        $this->getEntityManager()->flush();
    }

    public function append(StoredEvent $storedEvent): void
    {
        $this->getEntityManager()->persist($storedEvent);
    }
}
