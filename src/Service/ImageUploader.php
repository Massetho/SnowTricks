<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 26/04/2018
 * @time: 16:05
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    /**
     * @var string $targetDirectory
     */
    private $targetDirectory;

    /**
     * ImageUploader constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file) :string
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDirectory() :string
    {
        return $this->targetDirectory;
    }
}