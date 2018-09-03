<?php
/**
 * @description : Abstract Entity Listener for shared functions
 * @Author : Quentin Thomasset
 */

namespace App\EventListener;

class AbstractEntityListener
{
    /**
     * @param $entity
     */
    public function setDateCreated($entity)
    {
        $date = new \DateTime();
        $entity->setDateCreated($date);
    }

    /**
     * @param $entity
     */
    public function setDateUpdated($entity)
    {
        $date = new \DateTime();
        $entity->setDateUpdated($date);
    }
}
