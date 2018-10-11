<?php
/**
 * @description : Testing for LoginController
 * @Author : quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/login");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0,
            $crawler->filter("span.text-danger")->count()
        );

        $form = $crawler->selectButton('login-button')->form();

        // set some values
        $form['_username'] = 'Lucas';
        $form['_password'] = 'WrongPassword';

        // submit the form
        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertEquals(1,
            $crawler->filter("span.text-danger")->count()
        );
        $this->assertContains(
            'Invalid credentials.',
            $client->getResponse()->getContent()
        );
    }
}