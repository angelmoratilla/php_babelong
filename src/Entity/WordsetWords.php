<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'wordset_words')]
class WordsetWords
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'word_id', type: 'integer')]
    private ?int $wordId = null;

    #[ORM\Column(name: 'wordset_id', type: 'integer')]
    private ?int $wordsetId = null;

    #[ORM\Column(name: 'order_tag', type: 'integer', nullable: true)]
    private ?int $orderTag = null;

    #[ORM\ManyToOne(targetEntity: Word::class)]
    #[ORM\JoinColumn(name: 'word_id', referencedColumnName: 'id')]
    private ?Word $word = null;

    #[ORM\ManyToOne(targetEntity: Wordset::class)]
    #[ORM\JoinColumn(name: 'wordset_id', referencedColumnName: 'id')]
    private ?Wordset $wordset = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWordId(): ?int
    {
        return $this->wordId;
    }

    public function setWordId(?int $wordId): self
    {
        $this->wordId = $wordId;
        return $this;
    }

    public function getWordsetId(): ?int
    {
        return $this->wordsetId;
    }

    public function setWordsetId(?int $wordsetId): self
    {
        $this->wordsetId = $wordsetId;
        return $this;
    }

    public function getOrderTag(): ?int
    {
        return $this->orderTag;
    }

    public function setOrderTag(?int $orderTag): self
    {
        $this->orderTag = $orderTag;
        return $this;
    }

    public function getWord(): ?Word
    {
        return $this->word;
    }

    public function setWord(?Word $word): self
    {
        $this->word = $word;
        return $this;
    }

    public function getWordset(): ?Wordset
    {
        return $this->wordset;
    }

    public function setWordset(?Wordset $wordset): self
    {
        $this->wordset = $wordset;
        return $this;
    }
}
