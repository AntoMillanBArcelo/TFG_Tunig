<?php

namespace App\Entity;

use App\Repository\PinturaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PinturaRepository::class)]
class Pintura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo_pintura = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getTipoPintura(): ?string
    {
        return $this->tipo_pintura;
    }

    public function setTipoPintura(string $tipo_pintura): static
    {
        $this->tipo_pintura = $tipo_pintura;

        return $this;
    }
}
