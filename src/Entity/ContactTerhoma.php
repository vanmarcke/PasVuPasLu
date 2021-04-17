<?php

namespace App\Entity;

use App\Repository\ContactTerhomaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass=ContactTerhomaRepository::class)
 */
class ContactTerhoma
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $entreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $fonction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="text")
     */
    protected $objet;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }
}
