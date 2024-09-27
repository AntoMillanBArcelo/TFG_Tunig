<?php
namespace App\Entity;

use App\Repository\TramoHorarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TramoHorarioRepository::class)]
class TramoHorario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $inicio = null;

    #[ORM\Column(type: "time")]
    private ?\DateTimeInterface $fin = null;

    #[ORM\ManyToOne(targetEntity: Horario::class, inversedBy: 'tramosHorarios')]
    private ?Horario $horario = null;

   
    public function __toString(): string
    {
        return $this->descripcion ?? '';
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(\DateTimeInterface $inicio): static
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

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
}
