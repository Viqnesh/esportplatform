<?php

namespace App\Entity;

use App\Repository\ScoresheetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScoresheetRepository::class)
 */
class Scoresheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=FixedMatch::class)
     */
    private $fixedMatch;
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scoreReport;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    public function getFixedMatch(): ?FixedMatch
    {
        return $this->fixedMatch;
    }

    public function setFixedMatch(?FixedMatch $fixedMatch): self
    {
        $this->fixedMatch = $fixedMatch;

        return $this;
    }

    public function getScoreReport(): ?string
    {
        return $this->scoreReport;
    }

    public function setScoreReport(?string $scoreReport): self
    {
        $this->scoreReport = $scoreReport;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

}
