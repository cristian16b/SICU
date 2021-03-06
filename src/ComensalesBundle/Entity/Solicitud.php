<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table(name="solicitud")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\SolicitudRepository")
 * @ORM\Table(indexes={
 *          @ORM\Index(name="solicitud_tarjeta", columns={"tarjeta_id"})
 *           })
 */
class Solicitud
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
     * @ORM\Column(name="autorizado_por", type="string", length=255)
     */
    private $autorizadoPor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="datetime")
     */
    private $fechaIngreso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_revision", type="datetime")
     */
    private $fechaRevision;
    
    /**
    * @ORM\ManyToOne(targetEntity="TipoComensal",cascade={"persist"})
    * @ORM\JoinColumn(name="tipo_comensal_id", referencedColumnName="id")
    */
    private $tipo_comensal;

    /**
    * @ORM\ManyToOne(targetEntity="TipoEstado")
    * @ORM\JoinColumn(name="tipo_estado_id", referencedColumnName="id")
    */
    private $tipo_estado;
    
    /**
    * @ORM\OneToOne(targetEntity="Foto", cascade={"persist"})
    * @ORM\JoinColumn(name="foto_id", referencedColumnName="id")
    */
    private $foto;
    
    /**
    * @ORM\OneToOne(targetEntity="Tarjeta")
    * @ORM\JoinColumn(name="tarjeta_id", referencedColumnName="id")
    */
    private $tarjeta;
    
    /**
    * @ORM\ManyToOne(targetEntity="MotivoRechazo")
    * @ORM\JoinColumn(name="motivo_rechazo_id", referencedColumnName="id")
    */
    private $motivo_rechazo;
    
     /**
     * @ORM\ManyToOne(targetEntity="Persona", cascade={"persist"})
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     */
    private $persona;

    /**
    * @ORM\ManyToOne(targetEntity="DetalleEnfermedad",cascade={"persist"})
    * @ORM\JoinColumn(name="enfermedad_id", referencedColumnName="id")
    */
    private $enfermedad;
    
    /**
     * @ORM\ManyToOne(targetEntity="Turno")
     * @ORM\JoinColumn(name="turno_id", referencedColumnName="id")
     */
    private $turno;
   
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set autorizadoPor
     *
     * @param string $autorizadoPor
     *
     * @return Solicitud
     */
    public function setAutorizadoPor($autorizadoPor)
    {
        $this->autorizadoPor = $autorizadoPor;

        return $this;
    }

    /**
     * Get autorizadoPor
     *
     * @return string
     */
    public function getAutorizadoPor()
    {
        return $this->autorizadoPor;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Solicitud
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaRevision
     *
     * @param \DateTime $fechaRevision
     *
     * @return Solicitud
     */
    public function setFechaRevision($fechaRevision)
    {
        $this->fechaRevision = $fechaRevision;

        return $this;
    }

    /**
     * Get fechaRevision
     *
     * @return \DateTime
     */
    public function getFechaRevision()
    {
        return $this->fechaRevision;
    }

    /**
     * Set tipoComensal
     *
     * @param \ComensalesBundle\Entity\TipoComensal $tipoComensal
     *
     * @return Solicitud
     */
    public function setTipoComensal(\ComensalesBundle\Entity\TipoComensal $tipoComensal = null)
    {
        $this->tipo_comensal = $tipoComensal;

        return $this;
    }

    /**
     * Get tipoComensal
     *
     * @return \ComensalesBundle\Entity\TipoComensal
     */
    public function getTipoComensal()
    {
        return $this->tipo_comensal;
    }

    /**
     * Set tipoEstado
     *
     * @param \ComensalesBundle\Entity\TipoEstado $tipoEstado
     *
     * @return Solicitud
     */
    public function setTipoEstado(\ComensalesBundle\Entity\TipoEstado $tipoEstado = null)
    {
        $this->tipo_estado = $tipoEstado;

        return $this;
    }

    /**
     * Get tipoEstado
     *
     * @return \ComensalesBundle\Entity\TipoEstado
     */
    public function getTipoEstado()
    {
        return $this->tipo_estado;
    }

    /**
     * Set foto
     *
     * @param \ComensalesBundle\Entity\Foto $foto
     *
     * @return Solicitud
     */
    public function setFoto(\ComensalesBundle\Entity\Foto $foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \ComensalesBundle\Entity\Foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set motivoRechazo
     *
     * @param \ComensalesBundle\Entity\MotivoRechazo $motivoRechazo
     *
     * @return Solicitud
     */
    public function setMotivoRechazo(\ComensalesBundle\Entity\MotivoRechazo $motivoRechazo = null)
    {
        $this->motivo_rechazo = $motivoRechazo;

        return $this;
    }

    /**
     * Get motivoRechazo
     *
     * @return \ComensalesBundle\Entity\MotivoRechazo
     */
    public function getMotivoRechazo()
    {
        return $this->motivo_rechazo;
    }

    /**
     * Set persona
     *
     * @param \ComensalesBundle\Entity\Persona $persona
     *
     * @return Solicitud
     */
    public function setPersona(\ComensalesBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \ComensalesBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set enfermedad
     *
     * @param \ComensalesBundle\Entity\DetalleEnfermedad $enfermedad
     *
     * @return Solicitud
     */
    public function setEnfermedad(\ComensalesBundle\Entity\DetalleEnfermedad $enfermedad = null)
    {
        $this->enfermedad = $enfermedad;

        return $this;
    }

    /**
     * Get enfermedad
     *
     * @return \ComensalesBundle\Entity\DetalleEnfermedad
     */
    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

    /**
     * Set turno
     *
     * @param \ComensalesBundle\Entity\Turno $turno
     *
     * @return Solicitud
     */
    public function setTurno(\ComensalesBundle\Entity\Turno $turno = null)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return \ComensalesBundle\Entity\Turno
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set tarjeta
     *
     * @param \ComensalesBundle\Entity\Tarjeta $tarjeta
     *
     * @return Solicitud
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
}
