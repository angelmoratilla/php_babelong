<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'word')]
class Word
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $simplified = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $traditional = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pinyin = null;

    #[ORM\Column(name: 'real_pinyin', type: 'string', length: 255, nullable: true)]
    private ?string $realPinyin = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $translation1 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $translation2 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $translation3 = null;

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSimplified(): ?string
    {
        return $this->simplified;
    }

    public function getTraditional(): ?string
    {
        return $this->traditional;
    }

    public function getPinyin(): ?string
    {
        return $this->pinyin;
    }

    public function getRealPinyin(): ?string
    {
        return $this->realPinyin;
    }

    public function getTranslation1(): ?string
    {
        return $this->translation1;
    }

    public function getTranslation2(): ?string
    {
        return $this->translation2;
    }

    public function getTranslation3(): ?string
    {
        return $this->translation3;
    }

    // Setters

    public function setSimplified(?string $simplified): self
    {
        $this->simplified = $simplified;
        return $this;
    }

    public function setTraditional(?string $traditional): self
    {
        $this->traditional = $traditional;
        return $this;
    }

    public function setPinyin(?string $pinyin): self
    {
        $this->pinyin = $pinyin;
        return $this;
    }

    public function setRealPinyin(?string $realPinyin): self
    {
        $this->realPinyin = $realPinyin;
        return $this;
    }

    public function setTranslation1(?string $translation1): self
    {
        $this->translation1 = $translation1;
        return $this;
    }

    public function setTranslation2(?string $translation2): self
    {
        $this->translation2 = $translation2;
        return $this;
    }

    public function setTranslation3(?string $translation3): self
    {
        $this->translation3 = $translation3;
        return $this;
    }
}
