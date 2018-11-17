<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialConsumos
 *
 * @ORM\Table(name="historial_consumos")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\HistorialConsumosRepository")
 */
class HistorialConsumos
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaConsumo", type="datetime")
     */
    private $fechaConsumo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaConsumo", type="time")
     */
    private $horaConsumo;


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
     * Set fechaConsumo
     *
     * @param \DateTime $fechaConsumo
     *
     * @return HistorialConsumos
     */
    public function setFechaConsumo($fechaConsumo)
    {
        $this->fechaConsumo = $fechaConsumo;

        return $this;
    }

    /**
     * Get fechaConsumo
     *
     * @return \DateTime
     */
    public function getFechaConsumo()
    {
        return $this->fechaConsumo;
    }

    /**
     * Set horaConsumo
     *
     * @param \DateTime $horaConsumo
     *
     * @return HistorialConsumos
     */
    public function setHoraConsumo($horaConsumo)
    {
        $this->horaConsumo = $horaConsumo;

        return $this;
    }

    /**
     * Get horaConsumo
     *
     * @return \DateTime
     */
    public function getHoraConsumo()
    {
        return $this->horaConsumo;
    }
}

