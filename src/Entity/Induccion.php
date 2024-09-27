<?php

namespace App\Entity;

use App\Repository\InduccionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InduccionRepository::class)]
class Induccion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $velocidad_punta = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    /**
     * @var Collection<int, Coche>
     */
    #[ORM\OneToMany(targetEntity: Coche::class, mappedBy: 'induccion')]
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

    /**
     * @return Collection<int, Coche>
     */
    public function getCoches(): Collection
    {
        return $this->coches;
    }

    public function addCoche(Coche $coche): static
    {
        if (!$this->coches->contains($coche)) {
            $this->coches->add($coche);
            $coche->setInduccion($this);
        }

        return $this;
    }

    public function removeCoche(Coche $coche): static
    {
        if ($this->coches->removeElement($coche)) {
            // Set the owning side to null (unless already changed)
            if ($coche->getInduccion() === $this) {
                $coche->setInduccion(null);
            }
        }

        return $this;
    }

    public function getType() {
        return 'Induccion';
    }
}
