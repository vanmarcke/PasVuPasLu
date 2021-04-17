<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumReplyRepository")
 */
class ForumReply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez renseigner un message pour répondre à cette discussion")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Forum", inversedBy="forumReplies")
     */
    private $Forum;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumReplies")
     */
    private $UserPost;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getForum(): ?Forum
    {
        return $this->Forum;
    }

    public function setForum(?Forum $Forum): self
    {
        $this->Forum = $Forum;

        return $this;
    }

    public function getUserPost(): ?User
    {
        return $this->UserPost;
    }

    public function setUserPost(?User $UserPost): self
    {
        $this->UserPost = $UserPost;

        return $this;
    }
}
