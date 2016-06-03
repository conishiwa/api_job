<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class JobRepository extends EntityRepository
{

    public function findAllQueryBuilder($filter = '')
    {
        $qb = $this->createQueryBuilder('job')->orderBy("job.datetime","desc");
        if ($filter) {
            $qb->andWhere('job.titre LIKE :filter OR job.description LIKE :filter')
                ->setParameter('filter', '%'.$filter.'%');
        }
        return $qb;

    }
}