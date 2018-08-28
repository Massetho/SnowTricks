<?php
/**
 * @description : User Listener
 * @Author : Quentin Thomasset
 */

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\User;

class UserListener extends AbstractEntityListener
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $this->setDateCreated($entity);
    }
}
