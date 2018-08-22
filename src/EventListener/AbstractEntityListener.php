<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 04/05/2018
 * @time: 09:25
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
