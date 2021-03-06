<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carrera
 *
 * @ORM\Table(name="carrera")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\CarreraRepository")
 */
class Carrera
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
    private $nombre;
    
     /**
     * @ORM\ManyToOne(targetEntity="Facultad")
     * @ORM\JoinColumn(name="facultad_id", referencedColumnName="id")
     */
    private $facultad;


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
     * @return Carrera
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
     * Set facultad
     *
     * @param \ComensalesBundle\Entity\Facultad $facultad
     *
     * @return Carrera
     */
    public function setFacultad(\ComensalesBundle\Entity\Facultad $facultad = null)
    {
        $this->facultad = $facultad;

        return $this;
    }

    /**
     * Get facultad
     *
     * @return \ComensalesBundle\Entity\Facultad
     */
    public function getFacultad()
    {
        return $this->facultad;
    }
}
