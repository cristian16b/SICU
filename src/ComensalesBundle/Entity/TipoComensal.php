<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoComensal
 *
 * @ORM\Table(name="tipo_comensal")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\TipoComensalRepository")
 */
class TipoComensal
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombreComensal;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TipoComensal
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set nombreComensal
     *
     * @param string $nombreComensal
     *
     * @return TipoComensal
     */
    public function setNombreComensal($nombreComensal)
    {
        $this->nombreComensal = $nombreComensal;

        return $this;
    }

    /**
     * Get nombreComensal
     *
     * @return string
     */
    public function getNombreComensal()
    {
        return $this->nombreComensal;
    }
}
