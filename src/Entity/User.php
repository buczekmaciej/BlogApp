<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
     * @ORM\Column(type="string", length=15, unique=true)
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=125)
     */
    private $Email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Articles", mappedBy="user")
     */
    private $Article;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $joinedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Details", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private $details;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDisabled;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="User")
     */
    private $comments;

    public function __construct()
    {
        $this->Article = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->Article->contains($article)) {
            $this->Article[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->Article->contains($article)) {
            $this->Article->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(?\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getDetails(): ?Details
    {
        return $this->details;
    }

    public function setDetails(?Details $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(?bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comments): self
    {
        if (!$this->comments->contains($comments)) {
            $this->comments[] = $comments;
            $comments->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comments): self
    {
        if ($this->comments->contains($comments)) {
            $this->comments->removeElement($comments);
            // set the owning side to null (unless already changed)
            if ($comments->getUser() === $this) {
                $comments->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = "ROLE_USER";

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
    }
    public function eraseCredentials()
    {
    }
}
