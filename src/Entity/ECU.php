<?php

namespace App\Entity;

use App\Repository\ECURepository;
use App\Repository\InduccionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ECURepository::class)]
class ECU
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
     * @var Collection<int, Coche>
     */
    #[ORM\OneToMany(targetEntity: Coche::class, mappedBy: 'ecu')]
    private Collection $coches;

    public function __construct()
    {
        $this->coches = new ArrayCollection();
    }

    // Getters y setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVelocidadPunta(): ?string
    {
        return $this->velocidad_punta;
    }

    public function setVelocidadPunta(string $velocidad_punta): static
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
    public function getCoches(): Collection
    {
        return $this->coches;
    }

    public function addCoche(Coche $coche): static
    {
        if (!$this->coches->contains($coche)) {
            $this->coches->add($coche);
            $coche->setECU($this);
        }

        return $this;
    }

    public function removeCoche(Coche $coche): static
    {
        if ($this->coches->removeElement($coche)) {
            if ($coche->getECU() === $this) {
                $coche->setECU(null);
            }
        }

        return $this;
    }
    public function getType() {
        return 'ECU';
    }

    public function __toString(): string
    {
        return $this->descripcion ?? '';
    }
}
