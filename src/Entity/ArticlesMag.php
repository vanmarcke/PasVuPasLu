<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Repository\ArticlesMagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticlesMagRepository::class)
 */
class ArticlesMag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titles;

    /**
     * @ORM\Column(type="text")
     */
    private $contents;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Assert\Image(
     *     mimeTypes= {"image/gif", "image/jpeg", "image/png"},
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M", maxSizeMessage="Attention, votre image est trop lourde.",
     * )
     */
    private $imagArt;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitles(): ?string
    {
        return $this->titles;
    }

    public function setTitles(string $titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getSlug() {

        return (new Slugify())->slugify($this->titles);

    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(?string $cle): self
    {
        $this->cle = $cle;

        return $this;
    }

    public function getImagArt(): ?string
    {
        return $this->imagArt;
    }

    public function setImagArt(?string $imagArt): self
    {
        $this->imagArt = $imagArt;

        return $this;
    }
}
