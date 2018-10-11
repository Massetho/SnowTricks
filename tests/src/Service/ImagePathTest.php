<?php
/**
 * @description : Test ImagePath Service
 * @Author : Quentin Thomasset
 */

namespace Tests\App\Entity;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImagePathTestTest extends WebTestCase
{
    public function testImagePath()
    {
        $image = new Image();
        $image->setFile('default.jpg');

        $this->assertTrue(!$image->getFile() instanceof File);
        $this->assertTrue(empty($image->getPath()));
        $this->assertTrue(empty($image->getWebPath()));

        self::bootKernel();
        $container = self::$container;

        $imgPath = $container->get('App\Service\ImagePath');
        $imgPath->setPath($image);

        $this->assertTrue(!empty($image->getPath()));
        $this->assertTrue(!empty($image->getWebPath()));
        $this->assertTrue($image->getFile() instanceof File);

    }
}