<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Importe
 *
 * @ORM\Table(name="importe")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\ImporteRepository")
 */
class Importe
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
     * @ORM\Column(name="nombreImporte", type="string", length=255)
     */
    private $nombreImporte;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=10, scale=2)
     */
    private $precio;


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
     * Set nombreImporte
     *
     * @param string $nombreImporte
     *
     * @return Importe
     */
    public function setNombreImporte($nombreImporte)
    {
        $this->nombreImporte = $nombreImporte;

        return $this;
    }

    /**
     * Get nombreImporte
     *
     * @return string
     */
    public function getNombreImporte()
    {
        return $this->nombreImporte;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return Importe
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }
}

