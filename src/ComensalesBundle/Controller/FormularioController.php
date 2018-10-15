<?php

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use ComensalesBundle\Entity\Foto;
use Symfony\Component\HttpFoundation\JsonResponse;
//provisorio
use ComensalesBundle\Entity\Turno;
use ComensalesBundle\Controller\FotoController;
use ComensalesBundle\Entity\Persona;
use ComensalesBundle\Entity\Solicitud;
use ComensalesBundle\Entity\DetalleEnfermedad;


/**
 * Description of FormularioController
 * 
 * @author Cristian B
 */
class FormularioController extends Controller
{
      /**
     * @Route("/formulario_i",name="formulario_I")
     * 
     */
    public function mostrarFormularioI()
    {
        return $this->render('Formulario registro/formularioI.html.twig');
    }
    
    /**
     * @Route("/formulario_ii",name="formulario_II")
     * 
     */
    public function mostrarFormularioII(Request $request)
    {
        $this->cargaSession($request);
        return $this->render('Formulario registro/formularioII.html.twig');
    }
   
    /*
     * cargaSession almacena todos los datos ingresados por el usuario en el formulario I
     * se almacena en la session creada
     */
    public function cargaSession(Request $request)
    {
        $session = new Session();
        $session->set('apellido',$request->request->get('apellido'));
        $session->set('nombre',$request->request->get('nombre'));
        $session->set('dni',$request->request->get('dni'));
        $session->set('tipo_comensal',$request->request->get('select_tipo'));
        $session->set('facultad',$request->request->get('select_facultad'));
        $session->set('carrera',$request->request->get('select_carrera'));
        $session->set('correo',$request->request->get('correo'));
        $session->set('codtelefono',$request->request->get('codtelefono'));
        $session->set('telefono',$request->request->get('telefono'));
        $session->set('celiaco',$request->request->get('celiaco'));
        $session->set('vegetariano',$request->request->get('vegetariano'));        
    }
    
    /**
     * @Route("/formulario_f",name="final") 
     */
    public function finalFormularioI()
    { 
        return $this->render('Formulario registro/formulario_c.html.twig');
    }
    
    
    
    //buscarTurnos retorna la lista de turnos disponibles en la fecha y sede solicitada
    //retorna un array vacio si no hay turnos
    /**
     * @Route("/turnos",name="turnos")
     * @Method({"GET"})
     */
    public function buscarTurnos(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $sede = $request->query->get('sede');
            $fecha_recibida = $request->query->get('fecha');
            $fecha = new \DateTime($fecha_recibida);

            $db = $this->getDoctrine()->getEntityManager();
            $qb = $db->createQueryBuilder();

            //escribo la consuLta
            $qb->select('t.horario,t.cupo')
               ->from('ComensalesBundle:Turno','t')
               ->where('t.dia = :fecha')
               ->andWhere('t.sede = :sede')
               ->setParameter('fecha',$fecha)
               ->setParameter('sede',$sede)
               ;
            //genero
            $q = $qb->getQuery();
            //consulto
            //$resultado = $q->getResult();
            $resultado = $q->getArrayResult();
            //retorno
            return new JsonResponse($resultado);
        }
    }
    
    
   
    
    /**
     * @Route("/guardar", name="agregar_persona")
     * @Method({"POST"})
     */
    public function gestionarSolicitud(Request $request)
    {
        /////la imagen preguntar si tiene mas de 31, sino esta vacia
        
        //los datos del formularioI vienen en session
        //los del II vienen en request
        $session = $request->getSession();
        $dni = $session->get('dni');
        
        //sino cargo el dni, corto la ejecución
        if(empty($dni))
        {
            throw $this->createNotFoundException('Error 1 - No cargo su información personal');
        }
        
        //recupero la foto (si la cargo)
        $file = $request->files->get('archivo');
        $canvas = $request->request->get('base64');
        
        //quito los espacios,puntos y demas chars, solo quedan los numeros
        //ejemplo 34.882.010 -> 34882010
        $Dni = $this->acomodaDni($dni);
        
        //genero una fecha generica (1-1-año actual)
        $fecha = new \DateTime();
        $fecha->setDate(date("Y"), 1,1);
        
        //pregunto si hay una persona con ese dni
        $subconsulta = 'SELECT p.id FROM ComensalesBundle:Persona p where p.dni =  ' . $Dni ;
        //$consulta = 'SELECT s FROM ComensalesBundle:Solicitud s where s.persona = (' .$subconsulta. ')';
        
        //inicio doctrine
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();
        
        //escribo la consuLta
        //pregunto si hay una solicitud asociada a la persona (dni) y cuenta con una solicitud del presente año
        $qb->select('s')
           ->from('ComensalesBundle:Solicitud','s')
           ->where('s.persona = (' . $subconsulta . ')')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('fecha',$fecha)
            ;
        //genero
        $q = $qb->getQuery();
        //consulto
        $resultado = $q->getResult();
        
        //si no hay solicitud lo agrego  
        if(empty($resultado))
        {
          //llamo a agregar
          $this->agregarSolicitud($session,$request,$file,$canvas,$Dni);
        }
        //si hay una lo rechazo
        else
        {
            throw $this->createNotFoundException('Error 3 - Se cuenta con una solicitud del presente año');
        } 
        //redirecciono a la pantalla final
        return $this->redirect($this->generateUrl('final'));
    }

    
    //recivo el string dni y le quito los puntos
    public function acomodaDni($dni)
    {
        //primer argumento, elemento a reemplazar
        //segundo argumento, elemento por el que se debe cambbiar
        //tercer argumento, variable
        return str_replace('.','',$dni);
    }  
    
    
    public function agregarSolicitud($session,$request,$file,$canvas,$dni)
    {
        $tipo_comensal = $session->get('tipo_comensal');
        
        $persona = $this->agregarPersona($session,$dni);
           //leo los gustos alimenticios
        $celiaco = $session->get('celiaco');
        $vegetariano = $session->get('vegetariano');
        $detalle = $this->setDetalle($celiaco, $vegetariano);
            //subo la imagen
        $gestorFotos = new FotoController();
        $foto = $gestorFotos->seteoFoto($file,$canvas,$dni);
            //
             //guardo el turno
        $turno = $this->asignarTurno($request->request->get('fecha'),
                $request->request->get('sede'),
                $request->request->get('horario_turno')
                );
            //seteo la solicitud
        $solicitud = $this->seteoSolicitud($persona,
                $detalle,
                $tipo_comensal,
                $foto,
                $turno);   
        //seteo el detalle con la solicitud
        $detalle->setSolicitud($solicitud); 
       
            //decremento 
           //persisto en la base de datos
        $db = $this->getDoctrine()->getManager();
        $db->persist($persona);
        $db->persist($solicitud);
        $db->persist($detalle);
        $db->persist($foto);
        //$db->persist($turno);
        $db->flush(); 
        
           //cierro la session
        $session->invalidate();
    }
    
    public function seteoSolicitud($persona,$detalle,$tipo_comensal,$foto,$turno)
    {
          //creo una solicitud
        $solicitud = new Solicitud();
           //seteo los atributos de solitud
        $solicitud->setPersona($persona);
        $solicitud->setEnfermedad($detalle);
        $solicitud->setTipoComensal($this->obtieneTipo($tipo_comensal));
        $solicitud->setEnfermedad($detalle);
        $solicitud->setTipoEstado($this->definirEstado());
        $solicitud->setFoto($foto);   
        $solicitud->setFechaIngreso(new \DateTime());
             //guardo el turno
        $solicitud->setTurno($turno);
            
        return $solicitud;
    }
    
    /////
    public function asignarTurno($fecha_string,$sede,$horario_turno)
    {
        //guardo el turno
        $turno = new Turno();
            //
        $fecha = new \DateTime($fecha_string);
        $turno->setDia(new \DateTime($fecha_string));
        $turno->setSede($sede);
        $turno->setHorario($horario_turno);
        //meramente simbolico
        //CUIDADO ACA SE DEBE DECREMENTAR EN UNA UNIDAD LA CANTIDAD DE CUPO
        $turno->setCupo(0);
        
        //decremento en una unidad el cupo para esa sede y fecha
        $id = $this->decrementarTurno($fecha, $sede);
            
        //retorno
        return $id;
    }
    
    public function decrementarTurno($fecha,$sede)
    {
        //elimino un cupo
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();

        //escribo la consuLta
            $qb->select('t')
               ->from('ComensalesBundle:Turno','t')
               ->where('t.dia = :fecha')
               ->andWhere('t.sede = :sede')
               ->setParameter('fecha',$fecha)
               ->setParameter('sede',$sede)
               ;
        $q = $qb->getQuery();
        //consulto
        $resultado = $q->getResult();
        //decremento
        if(!empty($resultado))
        {
            $resultado[0]->setCupo($resultado[0]->getCupo()-1);
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - Turno no existente');
        }
        
        return $resultado[0];
    }
    
    public function incrementarTurno($fecha,$sede)
    {
        //elimino un cupo
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();

        //escribo la consuLta
            $qb->select('t')
               ->from('ComensalesBundle:Turno','t')
               ->where('t.dia = :fecha')
               ->andWhere('t.sede = :sede')
               ->setParameter('fecha',$fecha)
               ->setParameter('sede',$sede)
               ;
        $q = $qb->getQuery();
        //consulto
        $resultado = $q->getResult();
        //decremento
        if(!empty($resultado))
        {
            $resultado[0]->setCupo($resultado[0]->getCupo()+1);
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - Turno no existente');
        }
        
        return $resultado[0];
    }
    
    
    ////////////
    
    
    public function agregarPersona($session,$dni)
    {
        //var_dump($session);
        $nombre =  $this->acomodaNomApe($session->get('nombre'));
        $apellido = $this->acomodaNomApe($session->get('apellido'));
        $codTelefono = $this->acomoda($session->get('codtelefono'));
        $telefono = $this->acomoda($session->get('telefono'));
        $correo = $this->acomoda($session->get('correo'));
        $facultad = $this->acomoda($session->get('facultad'));
        //$dni = $this->acomodaDni($session->get('dni'));
        
        $db = $this->getDoctrine()->getEntityManager();
        $persona = $db->getRepository('ComensalesBundle:Persona')->findBydni($dni);
        
    //    var_dump($persona);
        if(empty($persona))
        {
            $persona = $this->nuevaPersona($apellido, $nombre, $dni, $codTelefono, $telefono, $correo);
        }
        else
        {
           $persona = $this->actualizarPersona($persona, $apellido, $nombre, $dni, $codTelefono, $telefono, $correo);
        }
       
        $persona->setCarrera($this->obtieneCarrera(
                $session->get('facultad')
                ,
                $session->get('carrera')
                ,
                  $session->get('tipo_comensal')
                )
                );
        
        //seteo la facultad
        $persona->setFacultad($this->obtenerFacultad($facultad));
      
     
            //retorno la persona
        return $persona;
    }
    
    public function nuevaPersona($apellido,$nombre,$dni,$codTelefono,$telefono,$correo)
    {
            $persona = new Persona();
                    //seteo atributos de persona
                $persona->setApellido($apellido);
                $persona->setNombre($nombre);
                $persona->setDni($dni);
                $persona->setCodTelefono($codTelefono);
                $persona->setTelefono($telefono);
                $persona->setCorreo($correo);
            
            return $persona;
    }
    
    public function actualizarPersona($persona,$apellido,$nombre,$dni,$codTelefono,$telefono,$correo)
    {
        $person = $persona[0];
                $person->setApellido($apellido);
                $person->setNombre($nombre);
                $person->setDni($dni);
                $person->setCodTelefono($codTelefono);
                $person->setTelefono($telefono);
                $person->setCorreo($correo);
       return $person;
    }


    ////////////
    
    public function obtenerFacultad($facultad)
    {
        var_dump($facultad);
        //$id = $this->obtenerFacultadId($facultad);
        $em = $this->getDoctrine()->getEntityManager();
        $facu = $em->getRepository('ComensalesBundle:Facultad')->findBynombreFacultad($facultad);
        //var_dump($facultad);
        //var_dump($tipoo);
        //var_dump($carrera);
        
        if(empty($facu))
        {
            throw $this->createNotFoundException('Error n°  - No se encontro su facultad');
        }
        return $facu[0];
    }
    
    
    
    public function obtieneCarrera($facultad,$carrera,$tipoo)
    {
        //si es estudiante lo busca
        if($tipoo != 1)
        {
            $carrera = 'NO DEFINIDO';
        }
        $em = $this->getDoctrine()->getEntityManager();
        $carrer = $em->getRepository('ComensalesBundle:Carrera')->findBynombre($carrera);
        //var_dump($facultad);
        //var_dump($tipoo);
        //var_dump($carrera);
        
        if(empty($carrer))
        {
            throw $this->createNotFoundException('Error n° 1 - No se encontro su carrera');
        }
        return $carrer[0];
    }
    
    public function obtieneTipo($tipo)
    {
        //var_dump($tipo);
        //$tipo = 3;
        //veo que viene
        if($tipo == '1')
        {
            $tipo = 'Estudiante de carrera de grado';
        }
            else if($tipo == '2')
            {
                $tipo = 'Docente - No docente';
            }
                else if($tipo == '3')
                {
                    $tipo = 'Invitado';
                }
                    else
                    {
                        $tipo = 'NO DEFINIDO';
                    }
        //busco repositorio
        $em = $this->getDoctrine()->getEntityManager();
        $tipocom = $em->getRepository('ComensalesBundle:TipoComensal')->findBynombreComensal($tipo);
        //retorno la fila encontrada
        return $tipocom[0];
    }

    public function definirEstado()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $estado = $em->getRepository('ComensalesBundle:TipoEstado')->findBynombreEstado('Pendiente');
        return $estado[0];
    }
    
    
    public function setDetalle($celiaco,$vegetariano)
    {   
        $Celiaco = true;
        $Vegetariano = true;
        
        if($celiaco !== "on")
        {
            $Celiaco = false;
        }
        
        if($vegetariano !== "on")
        {
            $Vegetariano = false;
        }
        
           //obtengo el detalle de enfemerdades gustos
        $detalle = new DetalleEnfermedad();
        $detalle->setCeliaco($Celiaco);
        $detalle->setVegetariano($Vegetariano);
        
            //retorno
        return $detalle;
    }
    /////////////////
    
    public function acomodaNomApe($variable)
    {
         if(empty($variable))
        {
            $variable = 'NO DEFINIDO';
        }
        else
        {
            $variable = ucwords(strtolower(trim($variable)));
        }
        return $variable;
    }
    
    public function acomoda($variable)
    {
        if(empty($variable))
        {
            $variable = 'NO DEFINIDO';
        }
        return $variable;
    }
}



