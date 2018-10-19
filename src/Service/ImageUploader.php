<?php
/**
 * @description : Service for managing image upload
 * @Author : Quentin Thomasset
 */

namespace App\Service;

use App\Entity\Trick;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
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
     * @param FormInterface $form
     * @param Trick $trick
     */
    public function handleForm(FormInterface $form, Trick $trick)
    {
        //Managing bottom images
        if ($form->has('bottomImages')) {
            foreach ($form->get('bottomImages') as $formBI) {
                $image = $formBI->getData();
                $trick->addBottomImages($image);
            }
        }
    }

    /**
     * @return string
     */
    public function getTargetDirectory() :string
    {
        return $this->targetDirectory;
    }
}
