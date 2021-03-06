<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialConsumos
 *
 * @ORM\Table(name="historial_consumos")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\HistorialConsumosRepository")
 * @ORM\Table(indexes={
 *          @ORM\Index(name="historialConsumo_tarjeta", columns={"tarjeta_id"}),
 *          @ORM\Index(name="historialConsumo_itemConumo", columns={"item_consumo_id"}),
 *          @ORM\Index(name="historialConsumo_sede", columns={"sede_id"})
 *           })
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
     * @ORM\Column(name="fechaConsumo", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $fechaHoraConsumo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="saldoConsumo", type="decimal", precision=10, scale=2)
     */
    private $saldoConsumo;

    /**
    * @ORM\ManyToOne(targetEntity="Tarjeta")
    * @ORM\JoinColumn(name="tarjeta_id", referencedColumnName="id")
    */
    private $tarjeta;

    /**
    * @ORM\ManyToOne(targetEntity="ItemConsumo")
    * @ORM\JoinColumn(name="item_consumo_id", referencedColumnName="id")
    */
    private $itemConsumo;
    
    /**
    * @ORM\ManyToOne(targetEntity="Sede")
    * @ORM\JoinColumn(name="sede_id", referencedColumnName="id")
    */
    private $sedeConsumo;

    public function __construct() 
    {
        $this->fechaHoraRecarga = new \DateTime();
    }
    
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
     * Set tarjeta
     *
     * @param \ComensalesBundle\Entity\Tarjeta $tarjeta
     *
     * @return HistorialConsumos
     */
    public function setTarjeta(\ComensalesBundle\Entity\Tarjeta $tarjeta = null)
    {
        $this->tarjeta = $tarjeta;

        return $this;
    }

    /**
     * Get tarjeta
     *
     * @return \ComensalesBundle\Entity\Tarjeta
     */
    public function getTarjeta()
    {
        return $this->tarjeta;
    }

    /**
     * Set itemConsumo
     *
     * @param \ComensalesBundle\Entity\ItemConsumo $itemConsumo
     *
     * @return HistorialConsumos
     */
    public function setItemConsumo(\ComensalesBundle\Entity\ItemConsumo $itemConsumo = null)
    {
        $this->itemConsumo = $itemConsumo;

        return $this;
    }

    /**
     * Get itemConsumo
     *
     * @return \ComensalesBundle\Entity\ItemConsumo
     */
    public function getItemConsumo()
    {
        return $this->itemConsumo;
    }

    /**
     * Set sedeConsumo
     *
     * @param \ComensalesBundle\Entity\Sede $sedeConsumo
     *
     * @return HistorialConsumos
     */
    public function setSedeConsumo(\ComensalesBundle\Entity\Sede $sedeConsumo = null)
    {
        $this->sedeConsumo = $sedeConsumo;

        return $this;
    }

    /**
     * Get sedeConsumo
     *
     * @return \ComensalesBundle\Entity\Sede
     */
    public function getSedeConsumo()
    {
        return $this->sedeConsumo;
    }

    /**
     * Set fechaHoraConsumo
     *
     * @param \DateTime $fechaHoraConsumo
     *
     * @return HistorialConsumos
     */
    public function setFechaHoraConsumo($fechaHoraConsumo)
    {
        $this->fechaHoraConsumo = $fechaHoraConsumo;

        return $this;
    }

    /**
     * Get fechaHoraConsumo
     *
     * @return \DateTime
     */
    public function getFechaHoraConsumo()
    {
        return $this->fechaHoraConsumo;
    }

    /**
     * Set saldoConsumo
     *
     * @param string $saldoConsumo
     *
     * @return HistorialConsumos
     */
    public function setSaldoConsumo($saldoConsumo)
    {
        $this->saldoConsumo = $saldoConsumo;

        return $this;
    }

    /**
     * Get saldoConsumo
     *
     * @return string
     */
    public function getSaldoConsumo()
    {
        return $this->saldoConsumo;
    }
}
