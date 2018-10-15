<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotivoRechazo
 *
 * @ORM\Table(name="motivo_rechazo")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\MotivoRechazoRepository")
 */
class MotivoRechazo
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
     * @ORM\Column(name="comentario", type="string", length=255)
     */
    private $comentario;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="TipoRechazo")
    * @ORM\JoinColumn(name="tipo_rechazo_id", referencedColumnName="id")
    */
    private $tipo_rechazo;


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
     * Set comentario
     *
     * @param string $comentario
     *
     * @return MotivoRechazo
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set tipoRechazo
     *
     * @param \ComensalesBundle\Entity\TipoRechazo $tipoRechazo
     *
     * @return MotivoRechazo
     */
    public function setTipoRechazo(\ComensalesBundle\Entity\TipoRechazo $tipoRechazo = null)
    {
        $this->tipo_rechazo = $tipoRechazo;

        return $this;
    }

    /**
     * Get tipoRechazo
     *
     * @return \ComensalesBundle\Entity\TipoRechazo
     */
    public function getTipoRechazo()
    {
        return $this->tipo_rechazo;
    }
}
