<?php
namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Job;

class UidGenerator
{

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Job) {
            return;
        }
        $entityManager = $args->getEntityManager();

        $uid = hash('sha256', $entity->getId() + "secretvalue1234");
        $entity->setUid($uid);

        $entityManager->flush();
    }

}