<?php

namespace App\Entity;

use App\Repository\BandeauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BandeauRepository::class)
 */
class Bandeau
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
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Image(
     *     mimeTypes= {"image/gif", "image/jpeg", "image/png"},
     *     mimeTypesMessage="Vérifiez le format de votre image.",
     *     maxSize="2M", maxSizeMessage="Attention, votre image est trop lourde. Le fichier ne doit pas faire plus de 2Mo",
     * )
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): self
    {
        $this->cle = $cle;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
