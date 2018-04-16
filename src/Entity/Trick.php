<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 16:57
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Unique
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\PostedAt
     */
    private $postedAt;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $description;

    //RELATIONSHIPS

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $group;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="trick")
     */
    private $trickLoggers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="trick")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick")
     */
    private $comments;

    //FONCTIONS

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->trickLoggers = new ArrayCollection();
    }

    //GETTERS & SETTERS

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @return Collection|TrickLogger[]
     */
    public function getTrickLoggers()
    {
        return $this->trickLoggers;
    }


}