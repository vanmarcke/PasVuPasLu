<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AllCouponsRepository")
 */
class AllCoupons
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $promo_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $coupon_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $adddate_at;
    
    public function __construct()
    {
        $this->adddate_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId($user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPromoId(): ?int
    {
        return $this->promo_id;
    }

    public function setPromoId($promo_id): self
    {
        $this->promo_id = $promo_id;

        return $this;
    }

    public function getCouponId(): ?int
    {
        return $this->coupon_id;
    }

    public function setCouponId($coupon_id): self
    {
        $this->coupon_id = $coupon_id;

        return $this;
    }

    public function getAdddateAt(): ?\DateTimeInterface
    {
        return $this->adddate_at;
    }

    public function setAdddateAt(\DateTimeInterface $adddate_at): self
    {
        $this->adddate_at = $adddate_at;

        return $this;
    }
}
