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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modelo3d = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id')]
    private ?User $usuario = null;

    #[ORM\ManyToOne(targetEntity: Motor::class)]
    #[ORM\JoinColumn(name: 'motor_id', referencedColumnName: 'id')]
    private ?Motor $motor = null;

    #[ORM\ManyToOne(targetEntity: ECU::class, inversedBy: 'coches')]
    #[ORM\JoinColumn(name: 'ecu_id', referencedColumnName: 'id', nullable: true)]
    private ?ECU $ecu = null;

    #[ORM\ManyToOne(targetEntity: Induccion::class, inversedBy: 'coches')]
    #[ORM\JoinColumn(name: 'induccion_id', referencedColumnName: 'id', nullable: true)]
    private ?Induccion $induccion = null;

    #[ORM\ManyToOne(targetEntity: SistemaDeCombustible::class, inversedBy: 'coches')]
    #[ORM\JoinColumn(name: 'sistema_de_combustible_id', referencedColumnName: 'id', nullable: true)]
    private ?SistemaDeCombustible $sistemaDeCombustible = null;

    #[ORM\ManyToOne(targetEntity: Turbocompresor::class, inversedBy: 'coches')]
    #[ORM\JoinColumn(name: 'turbocompresor_id', referencedColumnName: 'id', nullable: true)]
    private ?Turbocompresor $turbocompresor = null;

    #[ORM\ManyToOne(targetEntity: Nitro::class, inversedBy: 'coches')]
    #[ORM\JoinColumn(name: 'nitro_id', referencedColumnName: 'id', nullable: true)]
    private ?Nitro $nitro = null;

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

    public function getModelo3d(): ?string
    {
        return $this->modelo3d;
    }

    public function setModelo3d(?string $modelo3d): static
    {
        $this->modelo3d = $modelo3d;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

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

    public function getEcu(): ?ECU
    {
        return $this->ecu;
    }

    public function setEcu(?ECU $ecu): static
    {
        $this->ecu = $ecu;
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
}
