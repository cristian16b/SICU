<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemRecarga
 *
 * @ORM\Table(name="item_recarga")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\ItemRecargaRepository")
 */
class ItemRecarga
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
     * @ORM\Column(name="nombreItemRecarga", type="string", length=255)
     */
    private $nombreItemRecarga;


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
     * Set nombreItemRecarga
     *
     * @param string $nombreItemRecarga
     *
     * @return ItemRecarga
     */
    public function setNombreItemRecarga($nombreItemRecarga)
    {
        $this->nombreItemRecarga = $nombreItemRecarga;

        return $this;
    }

    /**
     * Get nombreItemRecarga
     *
     * @return string
     */
    public function getNombreItemRecarga()
    {
        return $this->nombreItemRecarga;
    }
}

