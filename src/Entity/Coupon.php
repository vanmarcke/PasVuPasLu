<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponRepository")
 */
class Coupon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez mettre un code promo")
     * @Assert\Length(max="20", maxMessage="Le code promo ne doit pas depasser les 20 caractères")
     */
    private $promo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creatdate_at;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date d'expiration du code promo doit être renseigné")
     * @Assert\Date(message="La date n'est pas correct")
     */
    private $expirdate_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;


    public function __construct()
    {
        $this->creatdate_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPromo(): ?string
    {
        return $this->promo;
    }

    public function setPromo(string $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getCreatdateAt(): ?\DateTimeInterface
    {
        return $this->creatdate_at;
    }

    public function setCreatdateAt(\DateTimeInterface $creatdate_at): self
    {
        $this->creatdate_at = $creatdate_at;

        return $this;
    }

    public function getExpirdateAt(): ?\DateTimeInterface
    {
        return $this->expirdate_at;
    }

    public function setExpirdateAt(\DateTimeInterface $expirdate_at): self
    {
        $this->expirdate_at = $expirdate_at;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }
}
