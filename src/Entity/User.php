<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $Login;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Role;

    public function __construct()
    {
        $this->Article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->Login;
    }

    public function setLogin(string $Login): self
    {
        $this->Login = $Login;

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

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(?string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }
}
