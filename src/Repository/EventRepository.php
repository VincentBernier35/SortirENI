<?php

namespace App\Repository;

use App\Entity\Event;
use Cassandra\Date;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Event[]
     */
    public function findFilteredEvents(int $idSite, \DateTime $startTime, \DateTime $endTime, int $promoterID = 0, string $key = '&', int $state = 6) {
        // en DQL
        $entityManager = $this->getEntityManager();

        $dql = "SELECT c FROM App\Entity\Event c JOIN c.state s JOIN c.users_events u".
                " WHERE c.startTime BETWEEN :dateMin AND :dateMax".
                " AND c.site = :idSite".
                " AND s.reference < (:state )";
        if ($promoterID != 0) {
            $dql = $dql . " AND c.promoter = :idPromoter";
        }
        if ($key != '&') {
            $dql = $dql . " AND c.name LIKE :keyword";
        }

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idSite',$idSite);
        $query->setParameter('state',$state);
        $query->setParameter('dateMin', $startTime->format('Y-m-d 00:00:00'));
        $query->setParameter('dateMax', $endTime->format('Y-m-d 23:59:59'));
        if ($promoterID != 0) {
            $query->setParameter('idPromoter', $promoterID);
        }
        if ($key != '&') {
            $query->setParameter('keyword', '%'.$key.'%');
        }

        return $query->getResult();
    }
}