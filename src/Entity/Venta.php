<?php

namespace App\Entity;

use App\Repository\VentaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VentaRepository::class)
 */
class Venta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hora;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trabajo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ingreso;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $creditos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="venta")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Day", inversedBy="venta")
     */
    private $day;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getHora(): ?\DateTimeInterface
    {
        return $this->hora;
    }

    public function setHora(?\DateTimeInterface $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTrabajo(): ?string
    {
        return $this->trabajo;
    }

    public function setTrabajo(?string $trabajo): self
    {
        $this->trabajo = $trabajo;

        return $this;
    }

    public function getIngreso(): ?float
    {
        return $this->ingreso;
    }

    public function setIngreso(?float $ingreso): self
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    public function getCosto(): ?float
    {
        return $this->costo;
    }

    public function setCosto(?float $costo): self
    {
        $this->costo = $costo;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day): void
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * @param mixed $creditos
     */
    public function setCreditos($creditos): void
    {
        $this->creditos = $creditos;
    }

    public function  __toString(){
        return '';
    }

    public function __toJson(){

        // $date =date("d-m-Y", strtotime($this->fecha));
        return '{"id":'.$this->id.',"descripcion":"'.$this->descripcion.'",'.
            // 'fecha:'.$date.','.
            '"trabajo":"'.$this->trabajo.'",'.
            '"ingreso":'.$this->ingreso.','.
            '"costo":'.$this->costo.','.
            '"creditos":'.$this->creditos.','.
            '"user":"'.$this->user->getUserName().'"'.
        '}';

    }
}
