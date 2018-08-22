<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 04/05/2018
 * @time: 10:24
 */

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Video;

class VideoListener extends AbstractEntityListener
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        $this->setDateCreated($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        // Check which fields were changes
        $changes = $args->getEntityChangeSet();

        // Declare a variable that will contain the name of the previous file, if exists.
        $previousFilename = null;

        // Verify if the file field was changed
        if (array_key_exists("url", $changes)) {
            // Update previous file name
            $previousFilename = $changes["file"][0];
        }

        // If no new brochure file was uploaded
        if (is_null($entity->getUrl())) {
            // Let original filename in the entity
            $entity->setFile($previousFilename);

            $this->setDateCreated($entity);
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Video) {
            return;
        }

        $entity->recomposeUrl();
    }
}
