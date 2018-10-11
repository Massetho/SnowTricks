<?php
/**
 * @description : Service for managing images path
 * @Author : Quentin Thomasset
 */

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\File;

class ImagePath
{
    /**
     * @var string $imgPath
     */
    private $imgPath;

    /**
     * @var string $webPath
     */
    private $webPath;

    //FUNCTIONS

    /**
     * Image constructor.
     * @param string $targetDirectory
     * @param string $webPath
     */
    public function __construct(string $targetDirectory, string $webPath)
    {
        $this->imgPath = $targetDirectory;
        $this->webPath = $webPath;
    }

    /**
     * @param Trick $trick
     */
    public function handleTrickImages(Trick $trick)
    {
        foreach ($trick->getImages() as $image) {
            $this->setPath($image);
        }
    }

    /**
     * @param Image $entity
     */
    public function setPath(Image $entity)
    {
        if ($fileName = $entity->getFile()) {
            $entity->setFile(new File($this->imgPath.$fileName));
            $entity->setPath($entity->getFile()->getPathname());
            $path = $this->webPath . $fileName;
            $entity->setWebPath($path);
        }
    }
}
