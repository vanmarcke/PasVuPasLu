<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponLivreRepository")
 */
class CouponLivre
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
    private $coupon;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

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
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoupon(): ?string
    {
        return $this->coupon;
    }

    public function setCoupon(string $coupon): self
    {
        $this->coupon = $coupon;

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
