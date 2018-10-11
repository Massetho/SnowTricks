<?php
/**
 * @description : Testing for TrickIndexController
 * @Author : quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickIndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(
            12,
            $crawler->filter('.trick-vignette')->count()
        );
    }
}