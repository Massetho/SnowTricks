<?php
/**
 * @description : Service for managing images path
 * @Author : Quentin Thomasset
 */

namespace App\Service;

use App\Entity\Image;

class ImagePath
{
    /**
     * @var string $imgPath
     */
    private $imgPath;

    //FUNCTIONS

    /**
     * Image constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->imgPath = $targetDirectory;
    }

    /**
     * @param Image $entity
     */
    public function setWebPath(Image $entity)
    {
        $name = $entity->getFile()->getFilename();
        if (is_string($name)) {
            $path = $this->imgPath . 'trick/' . $name;
            $entity->setWebPath($path);
        }
    }
}
