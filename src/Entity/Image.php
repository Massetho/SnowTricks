<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 20/04/2018
 * @time: 14:10
 */


namespace App\Entity;

class Image
{
    const IMG_PATH = 'img/';

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var Trick $trick
     */
    private $trick;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Setting image path based on Trick Id.
     */
    public function setPath()
    {
        $this->path = self::IMG_PATH .'trick/'. $this->getTrick()->getId();
    }

    /**
     * getting image path for House.
     */
    public static function getHomeImage()
    {
        $path = self::IMG_PATH . 'home/*.{jpg,jpeg,gif,png}';
        $images = glob($path, GLOB_BRACE);
        $x = count($images);
        if ($x > 1)
            return $images[rand(0, $x-1)];
        else
            return $images[0];
    }


    /**
     * @return mixed
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * @param mixed $trick
     */
    public function setTrick($trick)
    {
        $this->trick = $trick;
    }


}