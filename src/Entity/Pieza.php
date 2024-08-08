<?php

namespace App\Entity;

use App\Repository\PiezaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiezaRepository::class)]
class Pieza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(type: 'integer')]
    private ?int $velocidadPunta = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    #[ORM\ManyToOne(targetEntity: Motor::class, inversedBy: 'piezas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Motor $motor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
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

    public function getVelocidadPunta(): ?int
    {
        return $this->velocidadPunta;
    }

    public function setVelocidadPunta(int $velocidadPunta): static
    {
        $this->velocidadPunta = $velocidadPunta;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getMotor(): ?Motor
    {
        return $this->motor;
    }

    public function setMotor(?Motor $motor): static
    {
        $this->motor = $motor;

        return $this;
    }
}
