<?php

namespace App\Entity;

use App\Repository\SistemaDeCombustibleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SistemaDeCombustibleRepository::class)]
class SistemaDeCombustible
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $velocidad_punta = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    /**
     * @var Collection<int, Motor>
     */
    #[ORM\OneToMany(targetEntity: Motor::class, mappedBy: 'sistemaDeCombustible')]
    private Collection $motores;

    public function __construct()
    {
        $this->motores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVelocidadPunta(): ?int
    {
        return $this->velocidad_punta;
    }

    public function setVelocidadPunta(int $velocidad_punta): static
    {
        $this->velocidad_punta = $velocidad_punta;

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

    /**
     * @return Collection<int, Motor>
     */
    public function getMotores(): Collection
    {
        return $this->motores;
    }

    public function addMotor(Motor $motor): static
    {
        if (!$this->motores->contains($motor)) {
            $this->motores->add($motor);
            $motor->setSistemaDeCombustible($this);
        }

        return $this;
    }

    public function removeMotor(Motor $motor): static
    {
        if ($this->motores->removeElement($motor)) 
        {
            if ($motor->getSistemaDeCombustible() === $this) {
                $motor->setSistemaDeCombustible(null);
            }
        }

        return $this;
    }
    public function getType() {
        return 'Sistema de Combustible';
    }
}
