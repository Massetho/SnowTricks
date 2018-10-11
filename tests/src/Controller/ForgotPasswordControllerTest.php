<?php
/**
 * @description : Testing for ForgotPasswordController
 * @Author : Quentin Thomasset
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForgotPasswordControllerTest extends WebTestCase
{
    public function testForgotPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/forgot_password");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0,
            $crawler->filter("div.alert")->count()
        );
        $form = $crawler->selectButton('forpasssubmit')->form();

        // set some values
        $form['mail[email]'] = 'lucas@zdzdd.com';

        // submit the form
        $crawler = $client->submit($form);
        $this->assertContains(
            'No user found for this email address.',
            $client->getResponse()->getContent()
        );

        $this->assertEquals(1,
            $crawler->filter("div.alert")->count()
        );
    }
}