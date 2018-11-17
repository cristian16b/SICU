<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialRecargas
 *
 * @ORM\Table(name="historial_recargas")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\HistorialRecargasRepository")
 */
class HistorialRecargas
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
     * @ORM\Column(name="montonRecarga", type="decimal", precision=10, scale=2)
     */
    private $montonRecarga;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaRecarga", type="datetime")
     */
    private $fechaRecarga;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaRecarga", type="time")
     */
    private $horaRecarga;


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
     * Set montonRecarga
     *
     * @param string $montonRecarga
     *
     * @return HistorialRecargas
     */
    public function setMontonRecarga($montonRecarga)
    {
        $this->montonRecarga = $montonRecarga;

        return $this;
    }

    /**
     * Get montonRecarga
     *
     * @return string
     */
    public function getMontonRecarga()
    {
        return $this->montonRecarga;
    }

    /**
     * Set fechaRecarga
     *
     * @param \DateTime $fechaRecarga
     *
     * @return HistorialRecargas
     */
    public function setFechaRecarga($fechaRecarga)
    {
        $this->fechaRecarga = $fechaRecarga;

        return $this;
    }

    /**
     * Get fechaRecarga
     *
     * @return \DateTime
     */
    public function getFechaRecarga()
    {
        return $this->fechaRecarga;
    }

    /**
     * Set horaRecarga
     *
     * @param \DateTime $horaRecarga
     *
     * @return HistorialRecargas
     */
    public function setHoraRecarga($horaRecarga)
    {
        $this->horaRecarga = $horaRecarga;

        return $this;
    }

    /**
     * Get horaRecarga
     *
     * @return \DateTime
     */
    public function getHoraRecarga()
    {
        return $this->horaRecarga;
    }
}

