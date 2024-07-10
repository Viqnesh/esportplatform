<?php

namespace App\Entity;

use App\Repository\FixedMatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FixedMatchRepository::class)
 */
class FixedMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipeA;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipeB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Scoresheet::class)
     */
    private $sheet;

    /**
     * @ORM\ManyToOne(targetEntity=League::class)
     */
    private $league;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     */
    private $winner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipeA(): ?Team
    {
        return $this->equipeA;
    }

    public function setEquipeA(?Team $equipeA): self
    {
        $this->equipeA = $equipeA;

        return $this;
    }

    public function getEquipeB(): ?Team
    {
        return $this->equipeB;
    }

    public function setEquipeB(?Team $equipeB): self
    {
        $this->equipeB = $equipeB;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

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

    public function getSheet(): ?Scoresheet
    {
        return $this->sheet;
    }

    public function setSheet(?Scoresheet $sheet): self
    {
        $this->sheet = $sheet;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    public function getWinner(): ?Team
    {
        return $this->winner;
    }

    public function setWinner(?Team $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
