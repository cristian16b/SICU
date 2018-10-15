<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facultad
 *
 * @ORM\Table(name="facultad")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\FacultadRepository")
 */
class Facultad
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
    private $nombreFacultad;


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
     * @return Facultad
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
     * Set nombreFacultad
     *
     * @param string $nombreFacultad
     *
     * @return Facultad
     */
    public function setNombreFacultad($nombreFacultad)
    {
        $this->nombreFacultad = $nombreFacultad;

        return $this;
    }

    /**
     * Get nombreFacultad
     *
     * @return string
     */
    public function getNombreFacultad()
    {
        return $this->nombreFacultad;
    }
}
