<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'wordset')]
class Wordset
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'long_desc', type: 'text', nullable: true)]
    private ?string $longDesc = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $language = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $type = null;

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLongDesc(): ?string
    {
        return $this->longDesc;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    // Setters

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setLongDesc(?string $longDesc): self
    {
        $this->longDesc = $longDesc;
        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
