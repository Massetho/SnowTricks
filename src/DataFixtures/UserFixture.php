<?php
/**
 * @description : Creating original user
 * @Author : Quentin Thomasset
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var string $adminMail
     */
    private $adminMail;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param string $adminMail
     */
    public function __construct(UserPasswordEncoderInterface $encoder, $adminMail)
    {
        $this->encoder = $encoder;
        $this->adminMail = $adminMail;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');

        $password = $this->encoder->encodePassword($user, 'pass1234');
        $user->setPassword($password);
        $user->setEmail($this->adminMail);
        $user->addRole('ROLE_USER');
        $user->addRole('ROLE_ADMIN');

        $manager->persist($user);
        $manager->flush();
    }
}
