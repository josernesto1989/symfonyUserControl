<?php

namespace App\Entity;

use App\Repository\TipoPiezaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoPiezaRepository::class)
 */
class TipoPieza
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tipo;

    /**
     * @ORM\Column(type="float")
     */
    private $manoDeObra;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getManoDeObra(): ?float
    {
        return $this->manoDeObra;
    }

    public function setManoDeObra(float $manoDeObra): self
    {
        $this->manoDeObra = $manoDeObra;

        return $this;
    }
}
