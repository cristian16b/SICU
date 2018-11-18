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
     * @ORM\Column(name="fechaConsumo", type="datetime")
     */
    private $fechaConsumo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaConsumo", type="time")
     */
    private $horaConsumo;
    
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
     * Set fechaConsumo
     *
     * @param \DateTime $fechaConsumo
     *
     * @return HistorialConsumos
     */
    public function setFechaConsumo($fechaConsumo)
    {
        $this->fechaConsumo = $fechaConsumo;

        return $this;
    }

    /**
     * Get fechaConsumo
     *
     * @return \DateTime
     */
    public function getFechaConsumo()
    {
        return $this->fechaConsumo;
    }

    /**
     * Set horaConsumo
     *
     * @param \DateTime $horaConsumo
     *
     * @return HistorialConsumos
     */
    public function setHoraConsumo($horaConsumo)
    {
        $this->horaConsumo = $horaConsumo;

        return $this;
    }

    /**
     * Get horaConsumo
     *
     * @return \DateTime
     */
    public function getHoraConsumo()
    {
        return $this->horaConsumo;
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
     * Set sede
     *
     * @param \ComensalesBundle\Entity\Sede $sede
     *
     * @return HistorialConsumos
     */
    public function setSede(\ComensalesBundle\Entity\Sede $sede = null)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return \ComensalesBundle\Entity\Sede
     */
    public function getSede()
    {
        return $this->sede;
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
}