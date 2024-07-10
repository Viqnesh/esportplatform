<?php

namespace App\Entity;

use App\Repository\ClassementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassementRepository::class)
 */
class Classement
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
    private $equipe;

    /**
     * @ORM\Column(type="integer")
     */
    private $MP;

    /**
     * @ORM\Column(type="integer")
     */
    private $W;

    /**
     * @ORM\Column(type="integer")
     */
    private $L;

    /**
     * @ORM\ManyToOne(targetEntity=League::class)
     */
    private $league;

    /**
     * @ORM\Column(type="integer")
     */
    private $pts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipe(): ?Team
    {
        return $this->equipe;
    }

    public function setEquipe(?Team $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getMP(): ?int
    {
        return $this->MP;
    }

    public function setMP(int $MP): self
    {
        $this->MP = $MP;

        return $this;
    }

    public function getW(): ?int
    {
        return $this->W;
    }

    public function setW(int $W): self
    {
        $this->W = $W;

        return $this;
    }

    public function getL(): ?int
    {
        return $this->L;
    }

    public function setL(int $L): self
    {
        $this->L = $L;

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

    public function getPts(): ?int
    {
        return $this->pts;
    }

    public function setPts(int $pts): self
    {
        $this->pts = $pts;

        return $this;
    }
}
