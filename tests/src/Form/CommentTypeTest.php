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
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentTypeTest extends TypeTestCase
{
    private $validator;

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator),
        );
    }

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