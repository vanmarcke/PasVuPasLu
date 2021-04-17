<?php

namespace App\Entity;

use App\Repository\MagContentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MagContentsRepository::class)
 */
class MagContents
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image(
     *     mimeTypes="image/jpeg",
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M", maxSizeMessage="Attention, votre image est trop lourde.",
     * )
     */
    private $imagMag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $TextMag;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImagMag(): ?string
    {
        return $this->imagMag;
    }

    public function setImagMag(?string $imagMag): self
    {
        $this->imagMag = $imagMag;

        return $this;
    }

    public function getTextMag(): ?string
    {
        return $this->TextMag;
    }

    public function setTextMag(?string $TextMag): self
    {
        $this->TextMag = $TextMag;

        return $this;
    }

    public function getSlug() {

        return (new Slugify())->slugify($this->titles);

    }
}
