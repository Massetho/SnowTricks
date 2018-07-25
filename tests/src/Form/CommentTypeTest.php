<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 29/06/2018
 * @time: 16:01
 */



namespace App\Tests\Form;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{

    public function testCommentSubmitValidData()
    {
        $formData = array(
            'content' => 'test commentary message'
        );

        $objectToCompare = new Comment();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(CommentType::class, $objectToCompare);

        $object = new Comment();
        $object->setContent($formData['content']);
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