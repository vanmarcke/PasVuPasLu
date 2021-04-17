<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="comment", options={"row_format":"DYNAMIC"},)
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;
    /**
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer")
     * @Assert\Range(min=1,max=10)
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createAt", type="datetime")
     */
    private $createAt;

    /**
     * @var Livres
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Livres", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ChildComment", mappedBy="comment")
     * @ORM\JoinColumn(nullable=true)
     */
    private $childComments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rapport;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $coverRate;

    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->childComments = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreateAt()
    {
        $this->createAt = new \DateTime();
    }

    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Collection|ChildComment[]
     */
    public function getChildComments(): Collection
    {
        return $this->childComments;
    }

    public function addChildComment(ChildComment $childComment): self
    {
        if (!$this->childComments->contains($childComment)) {
            $this->childComments[] = $childComment;
            $childComment->setComment($this);
        }

        return $this;
    }

    public function removeChildComment(ChildComment $childComment): self
    {
        if ($this->childComments->contains($childComment)) {
            $this->childComments->removeElement($childComment);
            // set the owning side to null (unless already changed)
            if ($childComment->getComment() === $this) {
                $childComment->setComment(null);
            }
        }

        return $this;
    }

    /**
     * @return integer
     */
    public function getRate():? int
    {
        return $this->rate;
    }


    public function setRate(int $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return Livres
     */
    public function getLivre(): Livres
    {
        return $this->livre;
    }

    /**
     * @param Livres $livre
     */
    public function setLivre(Livres $livre): void
    {
        $this->livre = $livre;
    }

    public function getRapport(): ?bool
    {
        return $this->rapport;
    }

    public function setRapport(?bool $rapport): self
    {
        $this->rapport = $rapport;

        return $this;
    }

    public function getCoverRate(): ?int
    {
        return $this->coverRate;
    }

    public function setCoverRate(?int $coverRate): self
    {
        $this->coverRate = $coverRate;

        return $this;
    }

}
