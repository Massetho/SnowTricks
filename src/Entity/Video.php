<?php
/**
 * @description : Video entity.
 * @Author : Quentin Thomasset
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
        $tab = explode("=", $url);  // slash url in two

        // Set code & type
        $this->setCode($tab[1]);
        $this->setType('youtube');
    }

    private function dailymotionId($url)
    {
        $cas = explode("/", $url); // slash URL to isolate segments

        $idb = $cas[4];

        $bp = explode("_", $idb);  // We retrieve identification code

        $id = $bp[0];

        // Set code & type
        $this->setCode($id);
        $this->setType('dailymotion');
    }

    private function vimeoId($url)
    {
        $tableaux = explode("/", $url);  // slash URL to isolate segments

        $id = $tableaux[count($tableaux)-1];  // We retrieve identification code

        // Set code & type
        $this->setCode($id);
        $this->setType('vimeo');
    }

    /**
     * This Function is called during those 3 events
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractIdentif()
    {
        $url = $this->getUrl();

        //Detect which kind of video platform this video is about and call corresponding function
        if (preg_match("#^(http|https)://www.youtube.com/#", $url)) {
            $this->youtubeId($url);
        } elseif ((preg_match("#^(http|https)://www.dailymotion.com/#", $url))) {
            $this->dailymotionId($url);
        } elseif ((preg_match("#^(http|https)://vimeo.com/#", $url))) {
            $this->vimeoId($url);
        }
    }

    /**
     * @return string
     */
    private function url()
    {
        // Get code & type
        $control = $this->getType();
        $id = strip_tags($this->getCode());

        if ($control == 'youtube') {
            $embed = "https://www.youtube-nocookie.com/embed/".$id;
            return $embed;
        } elseif ($control == 'dailymotion') {
            $embed = "https://www.dailymotion.com/embed/video/".$id;
            return $embed;
        } elseif ($control == 'vimeo') {
            $embed = "https://player.vimeo.com/video/".$id;
            return $embed;
        }
    }

    /**
     *
     */
    public function recomposeUrl()
    {
        // Get code & type
        $control = $this->getType();
        $id = strip_tags($this->getCode());

        $url = '';
        if ($control == 'youtube') {
            $url = "https://www.youtube.com/watch?v=".$id;
        } elseif ($control == 'dailymotion') {
            $url = "https://www.dailymotion.com/video/".$id;
        } elseif ($control == 'vimeo') {
            $url = "https://player.vimeo.com/video/".$id;
        }

        if ($url !== '') {
            $this->setUrl($url);
        }
    }

    /**
     * @return string
     */
    public function getImage()
    {
        // Get code & type
        $control = $this->getType();
        $id = strip_tags($this->getCode());

        if ($control == 'youtube') {
            $image = 'https://img.youtube.com/vi/'. $id. '/hqdefault.jpg';
            return $image;
        } elseif ($control == 'dailymotion') {
            $image = 'https://www.dailymotion.com/thumbnail/150x120/video/'. $id. '' ;
            return $image;
        } elseif ($control == 'vimeo') {
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
