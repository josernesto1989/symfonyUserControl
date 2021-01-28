<?php

namespace App\Entity;

use App\Repository\DayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DayRepository::class)
 */
class Day
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="boolean")
     */
    private $open;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $internet;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $firmware;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sobrante;


    /*                   relations       */

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OtrosGastos", mappedBy="day")
     */
    private $gastos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PiezaAPagar", mappedBy="day")
     */
    private $piezaAPagar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Venta", mappedBy="day")
     */
    private $venta;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Week", inversedBy="day")
     */
    private $week;

    public function __construct()
    {
        $this->open = true ;
        $this->piezaAPagar =new ArrayCollection();
        $this->venta =new ArrayCollection();
        $this->gastos =new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * @param mixed $venta
     */
    public function setVenta($venta): void
    {
        $this->venta = $venta;
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * @param mixed $week
     */
    public function setWeek($week): void
    {
        $this->week = $week;
    }

    /**
     * @return mixed
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @param mixed $open
     */
    public function setOpen($open): void
    {
        $this->open = $open;
    }

    /**
     * @return mixed
     */
    public function getInternet()
    {
        return $this->internet;
    }

    /**
     * @param mixed $internet
     */
    public function setInternet($internet): void
    {
        $this->internet = $internet;
    }



    /**
     * @return mixed
     */
    public function getFirmware()
    {
        return $this->firmware;
    }

    /**
     * @param mixed $firmware
     */
    public function setFirmware($firmware): void
    {
        $this->firmware = $firmware;
    }

    /**
     * @return mixed
     */
    public function getPiezaAPagar()
    {
        return $this->piezaAPagar;
    }

    /**
     * @param mixed $piezaAPagar
     */
    public function setPiezaAPagar($piezaAPagar): void
    {
        $this->piezaAPagar = $piezaAPagar;
    }

    /**
     * @param PiezaAPagar $piezaAPagar
     */
    public function addPiezaAPagar($piezaAPagar): void
    {
        $this->piezaAPagar[] = $piezaAPagar;
    }

    /**
     * @return mixed
     */
    public function getGastos()
    {
        return $this->gastos;
    }

    /**
     * @param mixed $gastos
     */
    public function setGastos($gastos): void
    {
        $this->gastos = $gastos;
    }
    /**
     * @param OtrosGastos $gastos
     */
    public function addGastos($gastos): void
    {
        $this->gastos = $gastos;
    }

    /**
     * @return mixed
     */
    public function getSobrante()
    {
        return $this->sobrante;
    }

    /**
     * @param mixed $sobrante
     */
    public function setSobrante($sobrante): void
    {
        $this->sobrante = $sobrante;
    }
}
