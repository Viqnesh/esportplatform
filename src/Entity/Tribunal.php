<?php

namespace App\Entity;

use App\Repository\TribunalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TribunalRepository::class)
 */
class Tribunal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FixedMatch::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $fixedMatch;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class)
     */
    private $topic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFixedMatch(): ?FixedMatch
    {
        return $this->fixedMatch;
    }

    public function setFixedMatch(?FixedMatch $fixedMatch): self
    {
        $this->fixedMatch = $fixedMatch;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }
}
