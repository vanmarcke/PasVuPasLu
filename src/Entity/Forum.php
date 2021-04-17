<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumRepository")
 */
class Forum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un titre pour pour créer une nouvelle discussion")
     * @Assert\Length(
     *     max="50",
     *     maxMessage="Le titre ne doit pas depasser les 50 caracteres",
     *     min="5",
     *     minMessage="Le titre doit avoir au minimum 5 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez renseigner un message")
     * @Assert\Length(
     *     min="3",
     *     minMessage="Le message doit avoir au minimum plus de 3 caractères")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forums")
     */
    private $Forums;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumReply", mappedBy="Forum")
     */
    private $forumReplies;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->forumReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getForums(): ?User
    {
        return $this->Forums;
    }

    public function setForums(User $Forums): self
    {
        $this->Forums = $Forums;

        return $this;
    }

    public function getSlug() {

        return (new Slugify())->slugify($this->title);

    }

    /**
     * @return Collection|ForumReply[]
     */
    public function getForumReplies(): Collection
    {
        return $this->forumReplies;
    }

    public function addForumReply(ForumReply $forumReply): self
    {
        if (!$this->forumReplies->contains($forumReply)) {
            $this->forumReplies[] = $forumReply;
            $forumReply->setForum($this);
        }

        return $this;
    }

    public function removeForumReply(ForumReply $forumReply): self
    {
        if ($this->forumReplies->contains($forumReply)) {
            $this->forumReplies->removeElement($forumReply);
            // set the owning side to null (unless already changed)
            if ($forumReply->getForum() === $this) {
                $forumReply->setForum(null);
            }
        }

        return $this;
    }


}
