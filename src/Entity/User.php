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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Token;

/**
 * @ORM\Table(name="trick_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User
{
    /**
     * @var int id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string $password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string $dateCreated
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @var int $registered
     * @ORM\Column(type="integer")
     */
    private $registered;

    /**
     * @var array $tokens
     * @ORM\OneToMany(targetEntity="App\Entity\Token", mappedBy="user", cascade={"remove"})
     */
    private $tokens;

    /**
     * @var array $comments
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var array $trickLoggers
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="user", cascade={"remove"})
     */
    private $trickLoggers;

    //FONCTIONS

    /**
     * @param \App\Entity\Token $tokenEntity
     */
    public function __construct(Token $tokenEntity)
    {
        $this->tokenEntity = $tokenEntity;
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
     * @param Token $token
     */
    public function setToken($token)
    {
        $this->tokens[] = $token;
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