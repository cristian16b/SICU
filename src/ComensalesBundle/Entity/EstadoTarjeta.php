<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoTarjeta
 *
 * @ORM\Table(name="estado_tarjeta")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\EstadoTarjetaRepository")
 */
class EstadoTarjeta
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreEstadoTarjeta", type="string", length=255)
     */
    private $nombreEstadoTarjeta;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreEstadoTarjeta
     *
     * @param string $nombreEstadoTarjeta
     *
     * @return EstadoTarjeta
     */
    public function setNombreEstadoTarjeta($nombreEstadoTarjeta)
    {
        $this->nombreEstadoTarjeta = $nombreEstadoTarjeta;

        return $this;
    }

    /**
     * Get nombreEstadoTarjeta
     *
     * @return string
     */
    public function getNombreEstadoTarjeta()
    {
        return $this->nombreEstadoTarjeta;
    }
}

