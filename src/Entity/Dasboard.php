<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DasboardRepository")
 */
class Dasboard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $quate_auter;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Length(max="255", maxMessage="Vous ne pouvez pas dépasser les 255 caractères autorisé")
     */
    private $quate_insp;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $content_editable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuateAuter(): ?string
    {
        return $this->quate_auter;
    }

    public function setQuateAuter(string $quate_auter): self
    {
        $this->quate_auter = $quate_auter;

        return $this;
    }

    public function getQuateInsp(): ?string
    {
        return $this->quate_insp;
    }

    public function setQuateInsp(string $quate_insp): self
    {
        $this->quate_insp = $quate_insp;

        return $this;
    }

    public function getContentEditable(): ?string
    {
        return $this->content_editable;
    }

    public function setContentEditable(string $content_editable): self
    {
        $this->content_editable = $content_editable;

        return $this;
    }
}
