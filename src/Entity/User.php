<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'fw_user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => 0])]
    private ?bool $active = false;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $accessDate = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 1])]
    private ?int $hsklevel = 1;

    #[ORM\Column(type: 'string', length: 10, nullable: true, options: ['default' => 'en'])]
    private ?string $language = 'en';

    #[ORM\Column(name: 'federatedId', type: 'string', length: 255, nullable: true)]
    private ?string $federatedId = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true, options: ['default' => 'NORMAL'])]
    private ?string $quizMode = 'NORMAL';

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => 1])]
    private ?bool $useColors = true;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getAccessDate(): ?\DateTimeInterface
    {
        return $this->accessDate;
    }

    public function setAccessDate(?\DateTimeInterface $accessDate): static
    {
        $this->accessDate = $accessDate;
        return $this;
    }

    public function getHsklevel(): ?int
    {
        return $this->hsklevel;
    }

    public function setHsklevel(?int $hsklevel): static
    {
        $this->hsklevel = $hsklevel;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;
        return $this;
    }

    public function getFederatedId(): ?string
    {
        return $this->federatedId;
    }

    public function setFederatedId(?string $federatedId): static
    {
        $this->federatedId = $federatedId;
        return $this;
    }

    public function getQuizMode(): ?string
    {
        return $this->quizMode;
    }

    public function setQuizMode(?string $quizMode): static
    {
        $this->quizMode = $quizMode;
        return $this;
    }

    public function isUseColors(): ?bool
    {
        return $this->useColors;
    }

    public function setUseColors(?bool $useColors): static
    {
        $this->useColors = $useColors;
        return $this;
    }
}
