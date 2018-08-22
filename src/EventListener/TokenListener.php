<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 14/05/2018
 * @time: 19:04
 */

namespace App\EventListener;

use App\Entity\Token;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TokenListener extends AbstractEntityListener
{

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