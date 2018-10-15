<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\TurnoRepository")
 */
class Turno
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
     * @ORM\ManyToOne(targetEntity="Sede")
     * @ORM\JoinColumn(name="sede_id", referencedColumnName="id")
     */
    private $sede;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dia", type="date")
     */
    private $dia;

    /**
     * @var string
     *
     * @ORM\Column(name="horario", type="string", length=255)
     */
    private $horario;

    /**
     * @var int
     *
     * @ORM\Column(name="cupo", type="integer")
     */
    private $cupo;


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
     * Set sede
     *
     * @param string $sede
     *
     * @return Turno
     */
    public function setSede($sede)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return string
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * Set dia
     *
     * @param \DateTime $dia
     *
     * @return Turno
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \DateTime
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set horario
     *
     * @param string $horario
     *
     * @return Turno
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set cupo
     *
     * @param integer $cupo
     *
     * @return Turno
     */
    public function setCupo($cupo)
    {
        $this->cupo = $cupo;

        return $this;
    }

    /**
     * Get cupo
     *
     * @return int
     */
    public function getCupo()
    {
        return $this->cupo;
    }
}
