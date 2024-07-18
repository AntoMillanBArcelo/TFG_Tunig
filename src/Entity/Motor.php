<?php

namespace App\Entity;

use App\Repository\MotorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotorRepository::class)]
class Motor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Caballos = null;

    #[ORM\Column]
    private ?int $ParMaximo = null;

    #[ORM\Column]
    private ?int $MxS = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaballos(): ?int
    {
        return $this->Caballos;
    }

    public function setCaballos(int $Caballos): static
    {
        $this->Caballos = $Caballos;

        return $this;
    }

    public function getParMaximo(): ?int
    {
        return $this->ParMaximo;
    }

    public function setParMaximo(int $ParMaximo): static
    {
        $this->ParMaximo = $ParMaximo;

        return $this;
    }

    public function getMxS(): ?int
    {
        return $this->MxS;
    }

    public function setMxS(int $MxS): static
    {
        $this->MxS = $MxS;

        return $this;
    }
}
