<?php
/**
 * @description : Token Listener
 * @Author : Quentin Thomasset
 */

namespace App\EventListener;

use App\Entity\Token;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TokenListener extends AbstractEntityListener
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Token) {
            return;
        }
        $entity->generate();
        $this->setDateCreated($entity);
    }
}
