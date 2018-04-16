<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 17:24
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 */
class Group
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

    //GETTERS & SETTERS

    /**
     * @return Collection|Trick[]
     */
    public function getTricks()
    {
        return $this->tricks;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @param string $postedAt
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}