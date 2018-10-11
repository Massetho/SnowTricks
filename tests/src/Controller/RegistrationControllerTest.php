<?php
/**
 * @description : Testing for RegistrationController
 * @Author : Quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/register");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}