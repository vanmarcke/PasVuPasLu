<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{

    const ARE = [
        0 => 'Auteur',
        1 => 'Lecteur',        
        2 => 'Acteur du livre',
        3 => 'Journaliste',
        4 => 'Autre',
    ];

    const STATUS = [
        0 => 'Pas urgent',
        1 => 'Urgent'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom ne doit pas être vide")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse mail ne doit pas être vide")
     * @Assert\Email(message="L'adresse mail n'est pas valide")
     */
    protected $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(message="le numéro de téléphone n'est pas correct", pattern="/[0-9]{9}/")
     */
    protected $phone;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="L'objet du message ne doit pas être vide")
     */
    protected $objet;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez sélectionner le caractère de votre message")
     */
    protected $are;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez sélectionner l'urgence de votre message")
     */
    protected $status;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le message ne doit pas être vide")
     */
    protected $message;

    /**
     * @ORM\Column(type="string", length=255)
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA : Erreur de saisie, essayez encore."
     * )
     */
    protected $captchaCode;

    public function getCaptchaCode(): ?string
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode(string $captchaCode): self
    {
        $this->captchaCode = $captchaCode;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAre(): ?int
    {
        return $this->are;
    }

    public function setAre(int $are): self
    {
        $this->are = $are;

        return $this;
    }

    public function getAreType() :string {
        return self::ARE[$this->are];
    }

    public function getSTATUS(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusType() :string {
        return self::STATUS[$this->status];
    }
    
}
