<?php

namespace App\Repository;

use App\Model\Rsvp;
use Doctrine\ORM\EntityRepository;

/**
 * GuestRepository
 *
 * @author Sander Marechal
 */
class GuestRepository extends EntityRepository
{
    /**
     * Count ceremony attendance
     */
    public function countCeremony(): int
    {
        $qb = $this->createQueryBuilder('g');
        $qb->select('COUNT(g)')
            ->where('g.ceremony = :yes')
            ->setParameter('yes', Rsvp::YES);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Count party attendance
     */
    public function countParty(): int
    {
        $qb = $this->createQueryBuilder('g');
        $qb->select('COUNT(g)')
            ->where('g.party = :yes')
            ->setParameter('yes', Rsvp::YES);

        return $qb->getQuery()->getSingleScalarResult();
    }
}
