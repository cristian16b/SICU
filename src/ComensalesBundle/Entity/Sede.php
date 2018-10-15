<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sede
 *
 * @ORM\Table(name="sede")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\SedeRepository")
 */
class Sede
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
     * @ORM\Column(name="nombreSede", type="string", length=255)
     */
    private $nombreSede;


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
     * Set nombreSede
     *
     * @param string $nombreSede
     *
     * @return Sede
     */
    public function setNombreSede($nombreSede)
    {
        $this->nombreSede = $nombreSede;

        return $this;
    }

    /**
     * Get nombreSede
     *
     * @return string
     */
    public function getNombreSede()
    {
        return $this->nombreSede;
    }
}

