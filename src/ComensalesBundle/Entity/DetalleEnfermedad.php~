<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleEnfermedad
 *
 * @ORM\Table(name="detalle_enfermedad")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\DetalleEnfermedadRepository")
 */
class DetalleEnfermedad
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
     * @var bool
     *
     * @ORM\Column(name="celiaco", type="boolean")
     */
    private $celiaco;

    /**
     * @var bool
     *
     * @ORM\Column(name="vegetariano", type="boolean")
     */
    private $vegetariano;
    
    


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set celiaco
     *
     * @param boolean $celiaco
     *
     * @return DetalleEnfermedad
     */
    public function setCeliaco($celiaco)
    {
        $this->celiaco = $celiaco;

        return $this;
    }

    /**
     * Get celiaco
     *
     * @return boolean
     */
    public function getCeliaco()
    {
        return $this->celiaco;
    }

    /**
     * Set vegetariano
     *
     * @param boolean $vegetariano
     *
     * @return DetalleEnfermedad
     */
    public function setVegetariano($vegetariano)
    {
        $this->vegetariano = $vegetariano;

        return $this;
    }

    /**
     * Get vegetariano
     *
     * @return boolean
     */
    public function getVegetariano()
    {
        return $this->vegetariano;
    }

    /**
     * Set solicitud
     *
     * @param \ComensalesBundle\Entity\Solicitud $solicitud
     *
     * @return DetalleEnfermedad
     */
    public function setSolicitud(\ComensalesBundle\Entity\Solicitud $solicitud = null)
    {
        $this->solicitud = $solicitud;

        return $this;
    }

    /**
     * Get solicitud
     *
     * @return \ComensalesBundle\Entity\Solicitud
     */
    public function getSolicitud()
    {
        return $this->solicitud;
    }
}
