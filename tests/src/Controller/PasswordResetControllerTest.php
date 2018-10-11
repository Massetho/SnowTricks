<?php
/**
 * @description : Testing for PasswordResetController
 * @Author : quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordResetControllerTest extends WebTestCase
{
    public function testPasswordReset()
    {
        $client = static::createClient();
        $client->request('GET', "/password_reset/10/false");

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains(
            'Invalid token',
            $client->getResponse()->getContent()
        );
    }
}