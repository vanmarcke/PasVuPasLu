<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 * @UniqueEntity(
 *     fields={"categorie"},
 *     message="Cette catégorie existe déjà !"
 * )
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *    message="Ce champ ne peut pas être vide"
     * )
     */
    private $categorie;

    /**
     * @ORM\Column(length=255)
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M", maxSizeMessage="Attention, votre image est trop lourde.",
     * )
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Livres", mappedBy="categorie")
     */
    private $livre;

    /**
     * @return mixed
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * @param mixed $livre
     */
    public function setLivre($livre): void
    {
        $this->livre = $livre;
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

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

}
