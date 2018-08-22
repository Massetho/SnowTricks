<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 18/07/2018
 * @time: 11:49
 */

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $groupNames = array('grab',
            'rotation',
            'rotation désaxée',
            'flip',
            'one foot trick',
            'slide',
            'old school');

        foreach ($groupNames as $name) {
            $group = new Group();
            $group->setDateCreated(new \DateTime());
            $group->setName($name);
            $manager->persist($group);
        }

        $manager->flush();
    }
}
