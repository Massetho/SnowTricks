<?php
/**
 * @description : Creating trick fixtures
 * @Author : Quentin Thomasset
 */

namespace App\DataFixtures;

use App\Entity\Group;
use App\DataFixtures\GroupFixture;
use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\File;

class TrickFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @var string $targetDirectory
     */
    private $targetDirectory;

    /**
     * @var string $imgDir
     */
    private $imgDir;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $dir = $this->targetDirectory . '*.{jpg,jpeg}';
        $this->imgDir = glob($dir, GLOB_BRACE);

        if ($key = array_search($this->targetDirectory.'default.jpg', $this->imgDir)) {
            unset($this->imgDir[$key]);
        }
    }

    public function load(ObjectManager $manager)
    {
        if (empty($this->imgDir)) {
            return;
        }

        $groups = $manager->getRepository(Group::class)->findAll();

        $description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In orci odio, ultrices id dapibus et, fermentum quis ipsum. Suspendisse sit amet justo sem. Nullam mauris justo, mollis eget tortor eu, mattis sodales libero. Vestibulum at laoreet nunc. Proin quis velit vestibulum, efficitur mauris posuere, pharetra nisi. In sit amet urna vel mauris feugiat venenatis sit amet in augue. Nunc eleifend mattis metus, sed semper ex tincidunt quis. Aliquam ut felis consectetur, faucibus nunc id, porttitor mi. Phasellus viverra cursus mauris sed pretium. Vivamus id nunc justo. Nullam ultrices feugiat quam, ut congue dolor feugiat at. Sed tincidunt libero justo, in convallis neque semper vehicula. Morbi semper sem ut ligula viverra, quis tincidunt sapien viverra. Pellentesque pharetra justo vel leo tincidunt auctor.
                        \\nNunc turpis est, vestibulum eget congue ac, fermentum quis massa. Curabitur eu risus sed mauris maximus egestas non in metus. Nunc mattis justo quis maximus maximus. Aliquam erat volutpat. Vivamus bibendum posuere finibus. Ut porttitor nunc at eros rhoncus imperdiet. Pellentesque ultricies felis nisi, ac consequat urna tincidunt id. Praesent gravida rhoncus justo eget tempor. Sed semper, ligula eget malesuada suscipit, ligula ligula consequat est, eu mollis lacus ex vitae ligula. Mauris pharetra lacus magna, nec luctus lectus aliquam sed. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nisl est, cursus non imperdiet vel, vulputate et magna. Etiam ut feugiat leo. Vestibulum eu risus risus. Sed at enim pharetra nisi iaculis dignissim sit amet et lacus.
                        \\nSed quis risus in erat dictum viverra at eu nisi. Etiam eget elementum est. Nullam ultricies sed mauris nec tempor.';

        $trickNames = array('Ollie',
            'Nollie',
            'Switch ollie',
            'Fakie ollie',
            'Ninety-ninety',
            'Air-to-fakie',
            'Poptart',
            'One-Two',
            'A B',
            'Beef Carpaccio',
            'Beef Curtains',
            'Bloody Dracula',
            'Canadian Bacon',
            'Cannonball',
            'Chicken salad',
            'China air',
            'Crail',
            'Cross-rocket',
            'Drunk Driver',
            'Frontside grab/indy',
            'Gorilla',
            'Japan air',
            'Korean bacon',
            'Melon',
            'Method',
            'Mule kick',
            'Mute',
            'Nose grab',
            'Nuclear',
            'Pickpocket'
        );

        foreach ($trickNames as $name) {
            $trick = new Trick();
            $trick->setName($name);
            $trick->setDescription($description);
            $key = array_rand($groups);
            $trick->addGroup($groups[$key]);

            $images = $this->getImages();
            foreach ($images as $image) {
                $trick->addImage($image);
            }

            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getImages()
    {
        $images = array();
        $j = rand(1, 3);
        for ($i = 1; $i <= $j; $i++) {
            $image = new Image();
            $key = array_rand($this->imgDir);
            $img = new File($this->imgDir[$key]);
            $image->setFile($img->getFilename());
            $images[]=$image;
        }
        return $images;
    }

    public function getDependencies()
    {
        return array(
            GroupFixture::class,
        );
    }
}
