<?php
/**
 * @description : Comment Listener
 * @Author : Quentin Thomasset
 */

namespace App\EventListener;

use App\Entity\Comment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class CommentListener extends AbstractEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Comment) {
            return;
        }

        $this->setDateCreated($entity);
    }
}
