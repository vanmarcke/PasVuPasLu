<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LivresRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Livres
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner un titre")
     * @Assert\Length(max="200", maxMessage="Attention: votre titre ne doit pas dépasser les 200 caractéres")
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      },
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M", maxSizeMessage="Attention, votre image est trop lourde."
     * )
     */
    private $photo;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner un extract du livre")
     * @ORM\Column(type="text")
     */
    private $extract;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categories",
     *     inversedBy="livre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",
     *     inversedBy="livre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="livre")
     */
    private $comments;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;



    public function __construct()
    {
        $this->CreatedAt = new \DateTime();
        $this->comments = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie): void
    {
        $this->categorie = $categorie;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): void
    {
        $comment->setArticle($this);
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    public function getExtract(): ?string
    {
        return $this->extract;
    }

    public function setExtract(string $extract): self
    {
        $this->extract = $extract;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Livres
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getSlug() {

        return (new Slugify())->slugify($this->titre);

    }


    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }


}
