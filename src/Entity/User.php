<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     errorPath="email",
 *     message="Ce compte existe déjà !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner votre nom")
     * @Assert\Length(min="3", minMessage="Vous devez renseigner plus de 3 caractères pour le nom",
     *     max="50", maxMessage="vous ne pouvez pas dépasser les 50 caractères pour le nom")
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Vous devez renseigner votre prenom")
     * @Assert\Length(min="3", minMessage="Vous devez renseigner plus de 3 caractères pour le prénom",
     *     max="50", maxMessage="vous ne pouvez pas dépasser les 50 caractères pour le prénom")
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;


    /**
     * @Assert\NotBlank(message="Vous devez renseigner votre adresse email")
     * @Assert\Email(message="Vous devez renseigner une adresse email valide")
     * @Assert\Length(min="3", minMessage="Vous devez renseigner plus de 3 caractères pour l'adresse email",
     *     max="50", maxMessage="vous ne pouvez pas dépasser les 150 caractères pour l'adresse email")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un mot de passe")
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Votre mot de passe ne doit pas depasser les 255 caracteres",
     *     min="3",
     *     minMessage="Votre mot de passe doit avoir au minimum plus de 3 caracteres"
     * )
     */
    private $password;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegistration;

    /**
     * @ORM\OneToOne(targetEntity="ProfilUser", mappedBy="user")
     *
     */
    private $auter;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Livres", mappedBy="user")
     */
    private $livre;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];
    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Forum", mappedBy="Forums")
     */
    private $forums;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumReply", mappedBy="UserPost")
     */
    private $forumReplies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PrivateMessage", mappedBy="userSender")
     */
    private $privateMessages;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $IsLivre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $subscription_end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payer_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paypal_profil_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payer_status;



    public function __construct()
    {
        $this->dateRegistration = new \DateTime();
//        $roles[] = 'ROLE_USER';
        $this->livre = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->forums = new ArrayCollection();
        $this->forumReplies = new ArrayCollection();
        $this->privateMessages = new ArrayCollection();

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


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }




    /**
     * @return mixed
     */
    public function getDateRegistration()
    {
        return $this->dateRegistration;
    }

    /**
     * @param mixed $dateRegistration
     */
    public function setDateRegistration($dateRegistration): void
    {
        $this->dateRegistration = $dateRegistration;
    }

    /**
     * @return mixed
     */
    public function getAuter()
    {
        return $this->auter;
    }

    /**
     * @param mixed $auter
     */
    public function setAuter($auter): void
    {
        $this->auter = $auter;
    }

    /**
     * @return mixed
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * @param mixed $livre
     */
    public function setLivre($livre): void
    {
        $this->livre = $livre;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return array
     */
    public function getForums(): array
    {
        return $this->forums;
    }

    public function addForum(Forum $forum): self
    {
        if (!$this->forums->contains($forum)) {
            $this->forums[] = $forum;
            $forum->setForums($this);
        }

        return $this;
    }

    public function removeForum(Forum $forum): self
    {
        if ($this->forums->contains($forum)) {
            $this->forums->removeElement($forum);
            // set the owning side to null (unless already changed)
            if ($forum->getForums() === $this) {
                $forum->setForums(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getForumReplies(): array
    {
        return $this->forumReplies;
    }

    public function addForumReply(ForumReply $forumReply): self
    {
        if (!$this->forumReplies->contains($forumReply)) {
            $this->forumReplies[] = $forumReply;
            $forumReply->setUserPost($this);
        }

        return $this;
    }

    public function removeForumReply(ForumReply $forumReply): self
    {
        if ($this->forumReplies->contains($forumReply)) {
            $this->forumReplies->removeElement($forumReply);
            // set the owning side to null (unless already changed)
            if ($forumReply->getUserPost() === $this) {
                $forumReply->setUserPost(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPrivateMessages(): array
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): self
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages[] = $privateMessage;
            $privateMessage->setUserSender($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): self
    {
        if ($this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->removeElement($privateMessage);
            // set the owning side to null (unless already changed)
            if ($privateMessage->getUserSender() === $this) {
                $privateMessage->setUserSender(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken)
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return mixed
     */
    public function getisLivre(): ?string
    {
        return $this->IsLivre;
    }

    /**
     * @param mixed $IsLivre
     */
    public function setIsLivre(?string $IsLivre)
    {
        $this->IsLivre = $IsLivre;
    }

    public function getSubscriptionEnd(): ?\DateTimeInterface
    {
        return $this->subscription_end;
    }

    public function setSubscriptionEnd(?\DateTimeInterface $subscription_end): self
    {
        $this->subscription_end = $subscription_end;

        return $this;
    }

    public function getPayerId(): ?string
    {
        return $this->payer_id;
    }

    public function setPayerId(?string $payer_id): self
    {
        $this->payer_id = $payer_id;

        return $this;
    }

    public function getPaypalProfilId(): ?string
    {
        return $this->paypal_profil_id;
    }

    public function setPaypalProfilId(?string $paypal_profil_id): self
    {
        $this->paypal_profil_id = $paypal_profil_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayerStatus()
    {
        return $this->payer_status;
    }

    /**
     * @param mixed $payer_status
     */
    public function setPayerStatus($payer_status): void
    {
        $this->payer_status = $payer_status;
    }


}
