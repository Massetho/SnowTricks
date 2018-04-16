<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 17:10
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickLoggerRepository")
 */
class TrickLogger
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
     * @ORM\Column(type="string", length=5000)
     */
    private $message;

    //RELATIONSHIPS

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="trickloggers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="trickloggers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $trick;


    //FUNCTIONS

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * @param Trick $trick
     */
    public function setTrick($trick)
    {
        $this->trick = $trick;
    }

}