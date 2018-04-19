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
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



}