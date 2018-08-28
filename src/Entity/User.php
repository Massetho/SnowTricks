<?php
/**
 * @description : User entity.
 * @Author : Quentin Thomasset
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Token;

/**
 * @ORM\Table(name="trick_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="Username already taken")
 * @UniqueEntity("email", message="Email already taken")
 */
class User implements UserInterface, \Serializable
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Your username must be at least {{ limit }} characters long",
     *      maxMessage = "Your username cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @var string $email
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(
     *      max = 254,
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string $password
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @var \DateTime $dateCreated
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @var array $roles
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var array $tokens
     * @ORM\OneToMany(targetEntity="App\Entity\Token", mappedBy="user", cascade={"remove", "persist"})
     */
    private $tokens;

    /**
     * @var array $comments
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var array $trickLoggers
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="user", cascade={"remove", "persist"})
     */
    private $trickLoggers;

    /**
     * @var string $plainPassword
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 4,
     *      max = 4000,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    //FONCTIONS

    /**
     *
     */
    public function __construct()
    {
        $this->addRole('ROLE_USER');

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
     * @return User
     */
    public function addToken($token)
    {
        $token->setUser($this);
        $this->tokens->add($token);

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        if (!is_array($this->getRoles())) {
            $this->roles[] = $role;
        } else {
            if (!in_array($role, $this->getRoles())) {
                $this->roles[] = $role;
            }
        }
    }


    //OTHER FUNCTIONS

    //NEEDED FUNCTIONS FOR USERINTERFACE
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }

    //NEEDED FUNCTIONS FOR SERIALIZE
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}
