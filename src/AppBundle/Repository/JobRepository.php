<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class JobRepository extends EntityRepository
{

    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('job')->orderBy("job.datetime","desc");

    }
}