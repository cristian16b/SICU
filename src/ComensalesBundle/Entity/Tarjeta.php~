<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//referencia: https://stackoverflow.com/questions/9558085/creating-index-in-doctrine2-symfony2-throws-semantical-error
// como poner nombres personalizados a las relaciones de las fk
// y que no sean codigos alfanumericos al azar

/**
 * Tarjeta
 *
 * @ORM\Table(name="tarjeta")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\TarjetaRepository")
 * @ORM\Table(indexes={
 *          @ORM\Index(name="tarjeta_estadoTarjeta", columns={"estado_tarjeta_id"}),
 *          @ORM\Index(name="tarjeta_solicitud", columns={"solicitud_id"})  
 *           })
 */
class Tarjeta
{
    /**
     * 
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
     * @ORM\Column(name="fechaAlta", type="datetime")
     */
    private $fechaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="saldo", type="decimal", precision=10, scale=2)
     */
    private $saldo;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="fechaUltimoConsumo", type="datetime" ,nullable=true)
     */
    private $fechaUltimoConsumo;
    
    /**
    * @ORM\ManyToOne(targetEntity="EstadoTarjeta")
    * @ORM\JoinColumn(name="estado_tarjeta_id", referencedColumnName="id")
    */
    private $estadoTarjeta;
    
    /**
    * @ORM\ManyToOne(targetEntity="Solicitud")
    * @ORM\JoinColumn(name="solicitud_id", referencedColumnName="id")
    */
    private $solicitud;
    
     
    
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
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Tarjeta
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set saldo
     *
     * @param string $saldo
     *
     * @return Tarjeta
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return string
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set estadoTarjeta
     *
     * @param \ComensalesBundle\Entity\EstadoTarjeta $estadoTarjeta
     *
     * @return Tarjeta
     */
    public function setEstadoTarjeta(\ComensalesBundle\Entity\EstadoTarjeta $estadoTarjeta = null)
    {
        $this->estadoTarjeta = $estadoTarjeta;

        return $this;
    }

    /**
     * Get estadoTarjeta
     *
     * @return \ComensalesBundle\Entity\EstadoTarjeta
     */
    public function getEstadoTarjeta()
    {
        return $this->estadoTarjeta;
    }

    /**
     * Set solicitud
     *
     * @param \ComensalesBundle\Entity\Solicitud $solicitud
     *
     * @return Tarjeta
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

    /**
     * Set fechaUltimoConsumo
     *
     * @param \DateTime $fechaUltimoConsumo
     *
     * @return Tarjeta
     */
    public function setFechaUltimoConsumo($fechaUltimoConsumo)
    {
        $this->fechaUltimoConsumo = $fechaUltimoConsumo;

        return $this;
    }

    /**
     * Get fechaUltimoConsumo
     *
     * @return \DateTime
     */
    public function getFechaUltimoConsumo()
    {
        return $this->fechaUltimoConsumo;
    }
}
