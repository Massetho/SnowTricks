<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 15:04
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Token;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $username;

    /**
     * @ORM\Unique
     * @ORM\Column(type="string", length=125)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\PostedAt
     */
    private $postedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $registered;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Token", mappedBy="user")
     */
    private $tokens;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="user")
     */
    private $trickLoggers;

    //FONCTIONS

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @param mixed $postedAt
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    /**
     * @return integer
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param integer $registered
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }

    /**
     * @return Collection|TrickLogger[]
     */
    public function getTrickLoggers()
    {
        return $this->trickLoggers;
    }


    /**
     * @return Collection|Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    //OTHER FUNCTIONS

    /**
     * Generate and set a new token
     * @return $this
     */
    public function generateToken()
    {
        $token = new Token();
        $token->generate();
        $this->setToken($token);
        return $this;
    }
}