<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemConsumo
 *
 * @ORM\Table(name="item_consumo")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\ItemConsumoRepository")
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
}

