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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity("name")
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
     * @ORM\Column(type="string", length=125, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    
    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $description;

    //RELATIONSHIPS

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="tricks")
     * @ORM\JoinTable(name="tricks_groups",
     *      joinColumns={@JoinColumn(name="trick_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="trick", cascade={"remove"})
     */
    private $trickLoggers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="trick", cascade={"remove"})
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", cascade={"remove"})
     */
    private $comments;

    //FONCTIONS

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->trickLoggers = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
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
     * @return Collection|Group[]
     */
    public function getGroups()
    {
        return $this->groups;
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