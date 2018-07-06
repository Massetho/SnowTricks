<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 14/05/2018
 * @time: 18:57
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