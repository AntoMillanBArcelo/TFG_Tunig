<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocheRepository::class)]
class Coche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marca = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modelo = null;

    #[ORM\Column(nullable: true)]
    private ?int $año = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id')]
    private ?User $usuario = null;

    #[ORM\ManyToOne(targetEntity: Motor::class)]
    #[ORM\JoinColumn(name: 'motor_id', referencedColumnName: 'id')]
    private ?Motor $motor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(?string $marca): static
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(?string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getAño(): ?int
    {
        return $this->año;
    }

    public function setAño(?int $año): static
    {
        $this->año = $año;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

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
