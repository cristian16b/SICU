<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoRechazo
 *
 * @ORM\Table(name="tipo_rechazo")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\TipoRechazoRepository")
 */
class TipoRechazo
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
    private $nombreRechazo;


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
     * @return TipoRechazo
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
     * Set nombreRechazo
     *
     * @param string $nombreRechazo
     *
     * @return TipoRechazo
     */
    public function setNombreRechazo($nombreRechazo)
    {
        $this->nombreRechazo = $nombreRechazo;

        return $this;
    }

    /**
     * Get nombreRechazo
     *
     * @return string
     */
    public function getNombreRechazo()
    {
        return $this->nombreRechazo;
    }
}
