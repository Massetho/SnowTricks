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
use App\Entity\TopImage;
use App\Service\ImageUploader;
use App\Service\ImagePath;

class ImageUploadListener
{
    private $uploader;

    private $pathMaker;

    public function __construct(ImageUploader $uploader, ImagePath $pathMaker)
    {
        $this->uploader = $uploader;
        $this->pathMaker = $pathMaker;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ((!$entity instanceof Image) && (!$entity instanceof TopImage)) {
            return;
        }

        if ($fileName = $entity->getFile()) {
            $entity->setFile(new File($this->uploader->getTargetDirectory().$fileName));
            $entity->setPath($entity->getFile()->getPathname());
            $this->pathMaker->setWebPath($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preRemove(LifecycleEventArgs  $args)
    {
        $entity = $args->getEntity();

        $this->removeFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ((!$entity instanceof Image) && (!$entity instanceof TopImage)) {
            return;
        }

        // Check which fields were changes
        $changes = $args->getEntityChangeSet();

        // Declare a variable that will contain the name of the previous file, if exists.
        $previousFilename = null;

        // Verify if the file field was changed
        if(array_key_exists("file", $changes)){
            // Update previous file name
            $previousFilename = $changes["file"][0];
        }

        // If no new brochure file was uploaded
        if(is_null($entity->getFile())){
            // Let original filename in the entity
            $entity->setFile($previousFilename);

            // If a new brochure was uploaded in the form
        }else{
            // If some previous file exist
            if(!is_null($previousFilename)){
                $pathPreviousFile = $this->uploader->getTargetDirectory().$previousFilename;

                // Remove it
                if(file_exists($pathPreviousFile)){
                    unlink($pathPreviousFile);
                }
            }

            // Upload new file
            $this->uploadFile($entity);
        }

    }

    private function uploadFile($entity)
    {
        // upload only works for Image entities
        if ((!$entity instanceof Image) && (!$entity instanceof TopImage)) {
            return;
        }

        $file = $entity->getFile();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setFile($fileName);
        }
    }

    private function removeFile($entity)
    {
        if ((!$entity instanceof Image) && (!$entity instanceof TopImage)) {
            return;
        }

        if ($file = $entity->getPath()) {
            unlink($file);
        }

    }

}