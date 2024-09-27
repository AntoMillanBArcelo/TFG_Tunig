<?php

namespace App\Entity;

use App\Repository\MotorRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MotorRepository::class)]
class Motor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Caballos = null;

    #[ORM\Column(type: 'float')]
    private ?float $ParMaximo = null;

    #[ORM\Column(type: 'float')]
    private ?float $cuartoMilla = null;

    #[ORM\OneToMany(mappedBy: 'motor', targetEntity: Pieza::class, orphanRemoval: true)]
    private Collection $piezas;

    #[ORM\OneToMany(mappedBy: 'motor', targetEntity: Coche::class)]
    private Collection $coches;

    #[ORM\ManyToOne(inversedBy: 'id_motor')]
    #[ORM\JoinColumn(name: "ecu_id")]
    private ?ECU $eCU = null;



    #[ORM\ManyToOne(inversedBy: 'OneToMany')]
    private ?Induccion $induccion = null;

    #[ORM\ManyToOne(inversedBy: 'motor')]
    private ?SistemaDeCombustible $sistemaDeCombustible = null;

    #[ORM\ManyToOne(inversedBy: 'motor')]
    private ?Turbocompresor $turbocompresor = null;

    #[ORM\ManyToOne(inversedBy: 'relation')]
    private ?Nitro $nitro = null;

    public function __construct()
    {
        $this->piezas = new ArrayCollection();
        $this->coches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaballos(): ?int
    {
        return $this->Caballos;
    }

    public function setCaballos(int $Caballos): static
    {
        $this->Caballos = $Caballos;

        return $this;
    }

    public function getParMaximo(): ?float
    {
        return $this->ParMaximo;
    }

    public function setParMaximo(float $ParMaximo): static
    {
        $this->ParMaximo = $ParMaximo;

        return $this;
    }

    public function getCuartoMilla(): ?float
    {
        return $this->cuartoMilla;
    }

    public function setCuartoMilla(float $cuartoMilla): static
    {
        $this->cuartoMilla = $cuartoMilla;

        return $this;
    }

    /**
     * @return Collection<int, Pieza>
     */
    public function getPiezas(): Collection
    {
        return $this->piezas;
    }

    public function addPieza(Pieza $pieza): static
    {
        if (!$this->piezas->contains($pieza)) {
            $this->piezas->add($pieza);
            $pieza->setMotor($this);
        }

        return $this;
    }

    public function removePieza(Pieza $pieza): static
    {
        if ($this->piezas->removeElement($pieza)) {
            if ($pieza->getMotor() === $this) {
                $pieza->setMotor(null);
            }
        }

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
            $coche->setMotor($this);
        }

        return $this;
    }

    public function removeCoche(Coche $coche): static
    {
        if ($this->coches->removeElement($coche)) {
            if ($coche->getMotor() === $this) {
                $coche->setMotor(null);
            }
        }

        return $this;
    }

    public function getECU(): ?ECU
    {
        return $this->eCU;
    }

    public function setECU(?ECU $eCU): static
    {
        $this->eCU = $eCU;

        return $this;
    }

    public function getInduccion(): ?Induccion
    {
        return $this->induccion;
    }

    public function setInduccion(?Induccion $induccion): static
    {
        $this->induccion = $induccion;

        return $this;
    }

    public function getSistemaDeCombustible(): ?SistemaDeCombustible
    {
        return $this->sistemaDeCombustible;
    }

    public function setSistemaDeCombustible(?SistemaDeCombustible $sistemaDeCombustible): static
    {
        $this->sistemaDeCombustible = $sistemaDeCombustible;

        return $this;
    }

    public function getTurbocompresor(): ?Turbocompresor
    {
        return $this->turbocompresor;
    }

    public function setTurbocompresor(?Turbocompresor $turbocompresor): static
    {
        $this->turbocompresor = $turbocompresor;

        return $this;
    }

    public function getNitro(): ?Nitro
    {
        return $this->nitro;
    }

    public function setNitro(?Nitro $nitro): static
    {
        $this->nitro = $nitro;

        return $this;
    }
    public function getType() {
        return 'Motor';
    }

    public function __toString(): string
    {
        return $this->descripcion ?? '';
    }
}
