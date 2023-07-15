<?php

namespace App\Repository;

use App\Entity\Event;
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
    public function findFilteredEvents(int $idSite, int $idUser, \DateTime $startTime, \DateTime $endTime, int $promoterID, string $key = '-no search-', int $state = 6): array {
        // en DQL
        $entityManager = $this->getEntityManager();

        $dql = "SELECT c FROM App\Entity\Event c JOIN c.state s".
                " WHERE c.startTime BETWEEN :dateMin AND :dateMax".
                " AND s.reference < (:state )".
                " AND NOT (s.reference = 0 AND c.promoter != :idUser)";
        if ($idSite != 0) {
            $dql = $dql . " AND c.site = :idSite";
        }
        if ($promoterID != 0) {
            $dql = $dql . " AND c.promoter = :idPromoter";
        }
        if ($key != '-no search-') {
            $dql = $dql . " AND c.name LIKE :keyword";
        }

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idUser',$idUser);
        $query->setParameter('state',$state);
        $query->setParameter('dateMin', $startTime->format('Y-m-d 00:00:00'));
        $query->setParameter('dateMax', $endTime->format('Y-m-d 23:59:59'));
        if ($idSite != 0) {
            $query->setParameter('idSite', $idSite);
        }
        if ($promoterID != 0) {
            $query->setParameter('idPromoter', $promoterID);
        }
        if ($key != '-no search-') {
            $query->setParameter('keyword', '%'.$key.'%');
        }

        return $query->getResult();
    }

    /**
     * @return Event[]
     */
    public function findBasicEvents(int $idUser): array {
        // en DQL
        $entityManager = $this->getEntityManager();

        $dql = "SELECT c FROM App\Entity\Event c JOIN c.state s".
            " WHERE s.reference < 5".
            " AND NOT (s.reference = 0 AND c.promoter != :idUser)";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idUser',$idUser);

        return $query->getResult();
    }

}