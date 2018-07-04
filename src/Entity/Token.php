<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 11/04/2018
 * @time: 17:20
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 */
class Token
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
     * @ORM\Column(type="string", length=1500)
     */
    private $token;

    //RELATIONSHIPS

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tokens")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    //FUNCTIONS
    //GETTERS & SETTERS

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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
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



    //OTHER FUNCTIONS

    /**
     * Hydrate new token
     * @return $this
     */
    public function generate()
    {
        $this->setToken(md5(uniqid('', true)));
        return $this;
    }

    /**
     * @return bool
     */
    public function isValidToken()
    {
        $expirationPeriod = 5 * 24 * 60 * 60; // First number is number of days before expiration of a token
        $now = time();
        //$token = strtotime($this->getDateCreated());
        $token = $this->getDateCreated()->getTimestamp();
        $expirationTime = $token + $expirationPeriod;

        return ($now >= $expirationTime) ? false : true;
    }

}