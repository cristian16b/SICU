<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistorialRecargas
 *
 * @ORM\Table(name="historial_recargas")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\HistorialRecargasRepository")
 * @ORM\Table(indexes={
 *          @ORM\Index(name="historialRecarga_tarjeta", columns={"tarjeta_id"}),
 *          @ORM\Index(name="historialRecarga_itemRecarga", columns={"item_recarga_id"}),
 *          @ORM\Index(name="historialRecarga_sede", columns={"sede_id"})
 *           })
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
    private $montoRecarga;

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
    * @ORM\ManyToOne(targetEntity="Tarjeta")
    * @ORM\JoinColumn(name="tarjeta_id", referencedColumnName="id")
    */
    private $tarjeta;

    /**
    * @ORM\ManyToOne(targetEntity="ItemRecarga")
    * @ORM\JoinColumn(name="item_recarga_id", referencedColumnName="id")
    */
    private $itemRecarga;
    
    /**
    * @ORM\ManyToOne(targetEntity="Sede")
    * @ORM\JoinColumn(name="sede_id", referencedColumnName="id")
    */
    private $sedeRecarga;


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
    public function setMontoRecarga($montonRecarga)
    {
        $this->montoRecarga = $montoRecarga;

        return $this;
    }

    /**
     * Get montonRecarga
     *
     * @return string
     */
    public function getMontoRecarga()
    {
        return $this->montoRecarga;
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

    /**
     * Set tarjeta
     *
     * @param \ComensalesBundle\Entity\Tarjeta $tarjeta
     *
     * @return HistorialRecargas
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
     * Set itemRecarga
     *
     * @param \ComensalesBundle\Entity\ItemConsumo $itemRecarga
     *
     * @return HistorialRecargas
     */
    public function setItemRecarga(\ComensalesBundle\Entity\ItemConsumo $itemRecarga = null)
    {
        $this->itemRecarga = $itemRecarga;

        return $this;
    }

    /**
     * Get itemRecarga
     *
     * @return \ComensalesBundle\Entity\ItemConsumo
     */
    public function getItemRecarga()
    {
        return $this->itemRecarga;
    }

    /**
     * Set sedeRecarga
     *
     * @param \ComensalesBundle\Entity\Sede $sedeRecarga
     *
     * @return HistorialRecargas
     */
    public function setSedeRecarga(\ComensalesBundle\Entity\Sede $sedeRecarga = null)
    {
        $this->sedeRecarga = $sedeRecarga;

        return $this;
    }

    /**
     * Get sedeRecarga
     *
     * @return \ComensalesBundle\Entity\Sede
     */
    public function getSedeRecarga()
    {
        return $this->sedeRecarga;
    }
}
