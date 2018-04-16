<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 18:09
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\PostedAt
     */
    private $postedAt;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    //RELATIONSHIPS

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trick", mappedBy="group")
     */
    private $tricks;

    //FUNCTIONS

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks()
    {
        return $this->tricks;
    }

}