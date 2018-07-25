<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 20/04/2018
 * @time: 17:18
 */

namespace Tests\App\Entity;

use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testVideoURL()
    {
        $video = new Video();
        $video->setUrl('https://www.youtube.com/watch?v=l_XRrUWC_wg');
        $video->extractIdentif();

        $this->assertEquals('youtube', $video->getType());
        $this->assertEquals('l_XRrUWC_wg', $video->getCode());
        $video->recomposeUrl();

        $this->assertEquals('https://www.youtube.com/watch?v=l_XRrUWC_wg', $video->getUrl());
        $this->assertEquals('https://img.youtube.com/vi/l_XRrUWC_wg/hqdefault.jpg', $video->getImage());

    }
}