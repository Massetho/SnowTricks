<?php
/**
 * @description : Trick entity.
 * @Author : Quentin Thomasset
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 4,
     *      max = 120,
     *      minMessage = "Trick name must be at least {{ limit }} characters long",
     *      maxMessage = "Trick name cannot be longer than {{ limit }} characters"
     * )
     *
     * @ORM\Column(type="string", length=125, unique=true)
     */
    private $name;

    /**
     * @var \DateTime $date_created
     * @ORM\Column(type="datetime", name="date_created")
     */
    private $date_created;

    /**
     * @var \DateTime $dateUpdated
     * @ORM\Column(type="datetime", name="date_updated", nullable=true)
     */
    private $dateUpdated;

    
    /**
     * @Assert\Length(
     *      min = 12,
     *      max = 5000,
     *      minMessage = "Trick description must be at least {{ limit }} characters long",
     *      maxMessage = "Trick description cannot be longer than {{ limit }} characters"
     * )
     *
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
     * @ORM\OneToMany(targetEntity="App\Entity\TrickLogger", mappedBy="trick", cascade={"remove", "persist"})
     */
    private $trickLoggers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="trick", cascade={"remove", "persist"})     *
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="trick", cascade={"remove", "persist"})
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", cascade={"remove", "persist"})
     */
    private $comments;

    /**
     * @var string $slug
     */
    private $slug;

    //FONCTIONS

    public function __construct()
    {
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->trickLoggers = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    //GETTERS & SETTERS

    /**
     * @return integer
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId(int $id)
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;
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
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups() : Collection
    {
        return $this->groups;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments() : Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos() : Collection
    {
        return $this->videos;
    }

    /**
     * @return Collection|TrickLogger[]
     */
    public function getTrickLoggers() : Collection
    {
        return $this->trickLoggers;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages() : Collection
    {
        return $this->images;
    }

    /**
     * @return Image
     */
    public function getTopImage() : Image
    {
        if (!$this->getImages()->isEmpty()) {
            $topImage = $this->getImages()->first();
        } else {
            $topImage = new Image();
        }
        return $topImage;
    }

    /**
     * @return array|null
     */
    public function getBottomImages()
    {
        $images = $this->getImages()->toArray();
        if (is_array($images)) {
            array_shift($images);
            return $images;
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    //ADDERS

    /**
     * @param Image $image
     * @return Trick
     */
    public function addImage(Image $image)
    {
        $image->setTrick($this);
        $this->images->add($image);

        return $this;
    }

    /**
     * @param Group $group
     * @return Trick
     */
    public function addGroup(Group $group)
    {
        $this->groups->add($group);

        return $this;
    }

    /**
     * @param Image $image
     * @return Trick
     */
    public function setTopImage(Image $image)
    {
        $firstKey = $this->images->indexOf($this->images->first());
        if ($firstKey) {
            $image->setTrick($this);
            $this->images->set($image, $firstKey);
        } else {
            $this->addImage($image);
        }

        return $this;
    }

    /**
     * @param mixed $image
     * @return Trick
     */
    public function addBottomImages($image)
    {
        if ($image instanceof Image) {
            $this->addImage($image);
        }

        return $this;
    }

    /**
     * @param mixed $image
     * @return Trick
     */
    public function setBottomImages($image)
    {
        return $this->addBottomImages($image);
    }

    /**
     * @param Video $video
     * @return Trick
     */
    public function addVideo(Video $video)
    {
        $video->setTrick($this);
        $this->videos->add($video);

        return $this;
    }

    /**
     * @param TrickLogger $trickLogger
     * @return Trick
     */
    public function addTrickLogger(TrickLogger $trickLogger)
    {
        $trickLogger->setTrick($this);
        $this->trickLoggers->add($trickLogger);

        return $this;
    }

    /**
     * @param Comment $comment
     * @return Trick
     */
    public function addComment(Comment $comment)
    {
        $comment->setTrick($this);
        $this->comments->add($comment);

        return $this;
    }

    //REMOVERS

    /**
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }


    /**
     * @param Video $video
     */
    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }


    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }


    /**
     * @param TrickLogger $trickLogger
     */
    public function removeTrickLogger(TrickLogger $trickLogger)
    {
        $this->trickLoggers->removeElement($trickLogger);
    }

    //OTHER FUNCTIONS

    /**
     * URL slug generator.
     */
    public function setURLSlug()
    {
        if (!$this->getName()) {
            return;
        }

        $slug = str_replace(" ", "-", $this->getName());

        $this->setSlug(rawurlencode($slug));
    }
}
