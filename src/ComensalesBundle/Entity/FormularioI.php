<?php

namespace ComensalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormularioI
 *
 * @ORM\Table(name="formulario_i")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\FormularioIRepository")
 */
class FormularioI
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
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=255)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoComensal", type="string", length=255)
     */
    private $tipoComensal;

    /**
     * @var string
     *
     * @ORM\Column(name="facultad", type="string", length=255)
     */
    private $facultad;

    /**
     * @var string
     *
     * @ORM\Column(name="carrera", type="string", length=255)
     */
    private $carrera;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=255)
     */
    private $correo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoTelefono", type="string", length=255)
     */
    private $codigoTelefono;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="celiaco", type="string", length=255)
     */
    private $celiaco;

    /**
     * @var string
     *
     * @ORM\Column(name="vegetariano", type="string", length=255)
     */
    private $vegetariano;


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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return FormularioI
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return FormularioI
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set dni
     *
     * @param string $dni
     *
     * @return FormularioI
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set tipoComensal
     *
     * @param string $tipoComensal
     *
     * @return FormularioI
     */
    public function setTipoComensal($tipoComensal)
    {
        $this->tipoComensal = $tipoComensal;

        return $this;
    }

    /**
     * Get tipoComensal
     *
     * @return string
     */
    public function getTipoComensal()
    {
        return $this->tipoComensal;
    }

    /**
     * Set facultad
     *
     * @param string $facultad
     *
     * @return FormularioI
     */
    public function setFacultad($facultad)
    {
        $this->facultad = $facultad;

        return $this;
    }

    /**
     * Get facultad
     *
     * @return string
     */
    public function getFacultad()
    {
        return $this->facultad;
    }

    /**
     * Set carrera
     *
     * @param string $carrera
     *
     * @return FormularioI
     */
    public function setCarrera($carrera)
    {
        $this->carrera = $carrera;

        return $this;
    }

    /**
     * Get carrera
     *
     * @return string
     */
    public function getCarrera()
    {
        return $this->carrera;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return FormularioI
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set codigoTelefono
     *
     * @param string $codigoTelefono
     *
     * @return FormularioI
     */
    public function setCodigoTelefono($codigoTelefono)
    {
        $this->codigoTelefono = $codigoTelefono;

        return $this;
    }

    /**
     * Get codigoTelefono
     *
     * @return string
     */
    public function getCodigoTelefono()
    {
        return $this->codigoTelefono;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return FormularioI
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celiaco
     *
     * @param string $celiaco
     *
     * @return FormularioI
     */
    public function setCeliaco($celiaco)
    {
        $this->celiaco = $celiaco;

        return $this;
    }

    /**
     * Get celiaco
     *
     * @return string
     */
    public function getCeliaco()
    {
        return $this->celiaco;
    }

    /**
     * Set vegetariano
     *
     * @param string $vegetariano
     *
     * @return FormularioI
     */
    public function setVegetariano($vegetariano)
    {
        $this->vegetariano = $vegetariano;

        return $this;
    }

    /**
     * Get vegetariano
     *
     * @return string
     */
    public function getVegetariano()
    {
        return $this->vegetariano;
    }
}

