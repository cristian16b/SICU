<?php

namespace ComensalesBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="ComensalesBundle\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface 
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
     * @ORM\Column(name="nombreUsuario", type="string", length=255)
     */
    private $nombreUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasenia", type="string", length=255)
     */
    private $contrasenia;

    /**
     * @var string
     *
     * @ORM\Column(name="rolUsuario", type="string", length=255)
     */
    private $rolUsuario;


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
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     *
     * @return Usuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set contrasenia
     *
     * @param string $contrasenia
     *
     * @return Usuario
     */
    public function setContrasenia($contrasenia)
    {
        $this->contrasenia = $contrasenia;

        return $this;
    }

    /**
     * Get contrasenia
     *
     * @return string
     */
    public function getContrasenia()
    {
        return $this->contrasenia;
    }

    /**
     * Set rolUsuario
     *
     * @param string $rolUsuario
     *
     * @return Usuario
     */
    public function setRolUsuario($rolUsuario)
    {
        $this->rolUsuario = $rolUsuario;

        return $this;
    }

    /**
     * Get rolUsuario
     *
     * @return string
     */
    public function getRolUsuario()
    {
        return $this->rolUsuario;
    }
    
    public function getUsername()
    {
        return $this->nombreUsuario;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
      // En este caso definimos un rol fijo, en el caso de que tengamos un campo role en la tabla de la BBDD    tendríamos que hacer $this->getRole()
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
        $this->contrasenia = null;
    }
    
    public function getPassword()
    {
        return $this->contrasenia;
    }
    
    public function __toString() {
        return $this->nombreUsuario;
    }

        // NO PERSISTIDO EN LA BD
    
    private $plainPassword;
 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}

