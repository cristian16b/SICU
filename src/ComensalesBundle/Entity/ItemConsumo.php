<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemConsumo
 *
 * @ORM\Table(name="item_consumo")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\ItemConsumoRepository")
 * @ORM\Table(indexes={
 *          @ORM\Index(name="item_importe", columns={"importe_id"})
 *           })
 */
class ItemConsumo
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
     * @ORM\Column(name="nombreItemConsumo", type="string", length=255)
     */
    private $nombreItemConsumo;
    
    /**
    * @ORM\ManyToOne(targetEntity="Importe")
    * @ORM\JoinColumn(name="importe_id", referencedColumnName="id")
    */
    private $importe;


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
     * Set nombreItemConsumo
     *
     * @param string $nombreItemConsumo
     *
     * @return ItemConsumo
     */
    public function setNombreItemConsumo($nombreItemConsumo)
    {
        $this->nombreItemConsumo = $nombreItemConsumo;

        return $this;
    }

    /**
     * Get nombreItemConsumo
     *
     * @return string
     */
    public function getNombreItemConsumo()
    {
        return $this->nombreItemConsumo;
    }

    /**
     * Set importe
     *
     * @param \ComensalesBundle\Entity\Importe $importe
     *
     * @return ItemConsumo
     */
    public function setImporte(\ComensalesBundle\Entity\Importe $importe = null)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return \ComensalesBundle\Entity\Importe
     */
    public function getImporte()
    {
        return $this->importe;
    }
}
