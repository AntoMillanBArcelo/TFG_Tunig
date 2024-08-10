<?php

namespace App\Entity;

use App\Repository\CircuitoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CircuitoRepository::class)]
class Circuito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?int $ubicacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getUbicacion(): ?int
    {
        return $this->ubicacion;
    }

    public function setUbicacion(int $ubicacion): static
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }
}
