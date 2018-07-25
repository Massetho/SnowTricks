<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 29/06/2018
 * @time: 14:21
 */

namespace App\Tests\Form;

use App\Entity\Group;
use App\Entity\Image;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Entity\Trick;

use App\Repository\GroupRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Tests\Extension\Core\Type\BaseTypeTest;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Form\PreloadedExtension;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

class TrickTypeTest extends TypeTestCase
{
    private $objectManager;
    private $appEntityGroup;
    private $groupRepo;

    private $em;

    protected function setUp()
    {
        // mock any dependencies
        /*$this->em = DoctrineTestHelper::createTestEntityManager();
        $this->objectManager = $this->createMock(ManagerRegistry::class);
        //$this->appEntityGroup = $this->createMock(Group::class);

        $this->objectManager->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo('default'))
            ->will($this->returnValue($this->em));

        $this->objectManager->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($this->em));*/


        parent::setUp();
    }

    protected function getExtensions()
    {
        $em = DoctrineTestHelper::createTestEntityManager();
        $objectManager = $this->createMock(ManagerRegistry::class);

        $objectManager->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo('default'))
            ->will($this->returnValue($em));

        $objectManager->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($em));

        // create a type instance with the mocked dependencies
        $type = new EntityType($objectManager);


        $registryMock = $this->createMock(Registry::class);
        $registryMock->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo('default'))
            ->will($this->returnValue($em));

        $registryMock->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnValue($em));
        $groupRepository = new GroupRepository($registryMock);

        return array(
            // register the type instances with the PreloadedExtension
            new PreloadedExtension(array($type), array()),
            new PreloadedExtension(array($groupRepository), array()),
            //new PreloadedExtension(array($this->appEntityGroup), array())
        );
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em = null;
        $this->emRegistry = null;
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'name' => 'test',
            'date_created' => new \DateTime(),
            'description' => 'test description',
            'groups'=> new ArrayCollection(),
            'images'=> new ArrayCollection(),
        );

        $objectToCompare = new Trick();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(TrickType::class, $objectToCompare);

        $object = new Trick();
        $object->setName($formData['name']);
        $object->setDescription($formData['description']);
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