<?php
// src/Entity/Carrera.php

namespace App\Entity;

use App\Repository\CarreraRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: CarreraRepository::class)]
class Carrera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(targetEntity: Horario::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Horario $horario = null;

    // Nueva relación con Circuito
    #[ORM\ManyToOne(targetEntity: Circuito::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Circuito $circuito = null;

    #[ORM\OneToMany(mappedBy: 'carrera', targetEntity: Inscripcion::class)]
    private Collection $inscripciones;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;
        return $this;
    }

    public function getHorario(): ?Horario
    {
        return $this->horario;
    }

    public function setHorario(?Horario $horario): static
    {
        $this->horario = $horario;
        return $this;
    }

    public function getCircuito(): ?Circuito
    {
        return $this->circuito;
    }

    public function setCircuito(?Circuito $circuito): static
    {
        $this->circuito = $circuito;
        return $this;
    }

    public function getInscripciones(): Collection
    {
        return $this->inscripciones;
    }
}
