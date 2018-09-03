<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 16/07/2018
 * @time: 15:44
 */

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{

    public function testUserSubmitValidData()
    {
        $formData = array(
            'email' => 'testMail@gmail.com',
            'username' => 'testUser',
            'plainPassword' => array('first' => 'pass', 'second' => 'pass'),
        );

        $objectToCompare = new User();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(UserType::class, $objectToCompare);

        $object = new User();
        $object->setEmail($formData['email']);
        $object->setUsername($formData['username']);
        $object->setPlainPassword($formData['plainPassword']['first']);

        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}