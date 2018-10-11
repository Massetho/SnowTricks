<?php
/**
 * @description : Testing for TrickNewController
 * @Author : quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickNewControllerTest extends WebTestCase
{
    public function testNewTrick()
    {
        $client = static::createClient();
        $client->request('GET', "/admin/new/");
        $this->assertNotContains(
            'Please Login',
            $client->getResponse()->getContent()
        );

        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Please Login',
            $client->getResponse()->getContent()
        );

    }
}