<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 26/04/2018
 * @time: 16:26
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Image;
use App\Service\ImageUploader;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setFile(new File($this->uploader->getTargetDirectory().$fileName));
            $entity->setPath($entity->getFile()->getPathname());
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Image entities
        if (!$entity instanceof Image) {
            return;
        }

        $file = $entity->getFile();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setFile($fileName);
        }
    }

}