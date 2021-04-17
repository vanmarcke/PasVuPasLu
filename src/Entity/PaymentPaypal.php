<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class PaymentPaypal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transaction_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $payment_amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $payment_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoice_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payment_date_at;

    public function __construct()
    {
        $this->payment_date_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(?string $transaction_id): self
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function getPaymentAmount(): ?float
    {
        return $this->payment_amount;
    }

    public function setPaymentAmount(?float $payment_amount): self
    {
        $this->payment_amount = $payment_amount;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(?string $invoice_id): self
    {
        $this->invoice_id = $invoice_id;

        return $this;
    }

    public function getPaymentDateAt(): ?\DateTimeInterface
    {
        return $this->payment_date_at;
    }

    public function setPaymentDateAt(\DateTimeInterface $payment_date_at): self
    {
        $this->payment_date_at = $payment_date_at;

        return $this;
    }
}
