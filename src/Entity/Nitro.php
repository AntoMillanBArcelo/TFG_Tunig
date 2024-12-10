<?php

namespace App\Entity;

use App\Repository\NitroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NitroRepository::class)]
class Nitro
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
    #[ORM\OneToMany(targetEntity: Coche::class, mappedBy: 'nitro')]
    private Collection $coches;

    public function __construct()
    {
        $this->coches = new ArrayCollection();
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
            $coche->setNitro($this);
        }

        return $this;
    }

    public function removeCoche(Coche $coche): static
    {
        if ($this->coches->removeElement($coche)) 
        {
            if ($coche->getNitro() === $this) {
                $coche->setNitro(null);
            }
        }

        return $this;
    }
    public function getType() {
        return 'Nitro';
    }
}
