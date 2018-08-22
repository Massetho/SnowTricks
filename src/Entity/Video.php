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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @UniqueEntity("code")
 * @ORM\HasLifecycleCallbacks
 */
class Video
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $dateCreated;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="#^(http|https)://(www.youtube.com|www.dailymotion.com|vimeo.com)/#",
     *     match=true,
     *     message="The URL must be of a Youtube, DailyMotion or Vimeo video"
     * )
     */
    private $url;

    //RELATIONSHIPS

    /**
     * @var Trick $trick
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="videos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $trick;

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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Trick
     */
    public function getTrick() : Trick
    {
        return $this->trick;
    }

    /**
     * @param Trick $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Video
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }



    private function youtubeId($url)
    {
        $tableaux = explode("=", $url);  // découpe l’url en deux  avec le signe ‘=’

        $this->setCode($tableaux[1]);  // ajoute l’identifiant à l’attribut identif
        $this->setType('youtube');  // signale qu’il s’agit d’une video youtube et l’inscrit dans l’attribut $type
    }

    private function dailymotionId($url)
    {
        $cas = explode("/", $url); // On sépare la première partie de l'url des 2 autres

        $idb = $cas[4];  // On récupère la partie qui nous intéressent

        $bp = explode("_", $idb);  // On sépare l'identifiant du reste

        $id = $bp[0]; // On récupère l'identifiant

        $this->setCode($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('dailymotion'); // signale qu’il s’agit d’une video dailymotion et l’inscrit dans l’attribut $type
    }

    private function vimeoId($url)
    {
        $tableaux = explode("/", $url);  // on découpe l’url grâce au « / »

        $id = $tableaux[count($tableaux)-1];  // on reticent la dernière partie qui contient l’identifiant

        $this->setCode($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('vimeo');  // signale qu’il s’agit d’une video vimeo et l’inscrit dans l’attribut $type

    }

    /**
     * This Function is called during those 3 events
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractIdentif()
    {
        $url = $this->getUrl();  // on récupère l’url

        if (preg_match("#^(http|https)://www.youtube.com/#", $url))  // Si c’est une url Youtube on execute la fonction correspondante
        {
            $this->youtubeId($url);
        }
        else if((preg_match("#^(http|https)://www.dailymotion.com/#", $url))) // Si c’est une url Dailymotion on execute la fonction correspondante
        {
            $this->dailymotionId($url);
        }
        else if((preg_match("#^(http|https)://vimeo.com/#", $url))) // Si c’est une url Vimeo on execute la fonction correspondante
        {
            $this->vimeoId($url);
        }

    }

    private function url()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getCode()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $embed = "https://www.youtube-nocookie.com/embed/".$id;
            return $embed;
        }
        else if ($control == 'dailymotion')
        {
            $embed = "https://www.dailymotion.com/embed/video/".$id;
            return $embed;
        }
        else if($control == 'vimeo')
        {
            $embed = "https://player.vimeo.com/video/".$id;
            return $embed;
        }
    }

    public function recomposeUrl()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getCode()); // on récupère son identifiant

        $url = '';
        if($control == 'youtube')
        {
            $url = "https://www.youtube.com/watch?v=".$id;
        }
        else if ($control == 'dailymotion')
        {
            $url = "https://www.dailymotion.com/video/".$id;
        }
        else if($control == 'vimeo')
        {
            $url = "https://player.vimeo.com/video/".$id;
        }

        if ($url !== '')
            $this->setUrl($url);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getCode()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $image = 'https://img.youtube.com/vi/'. $id. '/hqdefault.jpg';
            return $image;
        }
        else if ($control == 'dailymotion')
        {
            $image = 'https://www.dailymotion.com/thumbnail/150x120/video/'. $id. '' ;
            return $image;
        }
        else if($control == 'vimeo')
        {
            $hash = unserialize(file_get_contents("https://vimeo.com/api/v2/video/".$id.".php"));
            $image = $hash[0]['thumbnail_small'];
            return $image;
        }

    }

    /**
     * @return string
     */
    public function getVideo()
    {
        $video = "<iframe width='100%' height='56.25%' src='".$this->url()."'  frameborder='0' allowfullscreen></iframe>";
        return $video;
    }


}