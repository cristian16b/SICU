<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComensalesBundle\Entity\MotivoRechazo;
use ComensalesBundle\Controller\FormularioController;
use ComensalesBundle\Controller\FotoController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Description of PanelComensales
 * @author Cristian B
 */
class SolicitudController extends Controller {
    /**
    * @Route("/solicitudes",name="solicitudes")    
    * @Security("has_role('ROLE_ADMINISTRATIVO')")  
    */
    public function mostrarPanel()
    {
        return $this->render('Panel solicitudes/panelSolicitudes.html.twig');
    }
    
    /**
    * @Route("/aceptar_s",name="aceptar_s")     
    * @Method({"GET"}) 
    */
    public function aceptarSolicitud(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el id
            $id = $request->query->get('id');
            //var_dump($id);
            
            //consulto
            $db = $this->getDoctrine()->getEntityManager();
            $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($id);
            $estados = $db->getRepository('ComensalesBundle:TipoEstado')->findBynombreEstado('Aceptado');
            //var_dump($solicitudes);
            
            //actualizo
            //si esta vacio no se encontro
            //se retornara por json un valor 0 que indica que no fue encontrado
            //se retorna 1 si se encontro y borro
            if(empty($solicitud) || empty($estados))
            {
                $resultado = array ('resultado ' => '0');
            }
            else 
            {
                //seteo
                
                $Estado = $estados[0];
                //veo si tiene motivo de rechazo, si lo fuere lo elimino
                $rechazo = $solicitud->getMotivoRechazo();
                //var_dump($rechazo);
                //if tiene un motivo de rechazo se elimina
                if($rechazo != null)
                {
                    $db->remove($rechazo);
                    $solicitud->setMotivoRechazo();
                }
                //cambiar a futuro
                $solicitud->setAutorizadoPor("@admin");
                //cambio el estado y actualizo
                $solicitud->setTipoEstado($Estado);
                //LA FECHA DE REVISION ES COLOCADA CON UN TRIGGER llamado setearFechaRevision
                $db->flush();
                
                $resultado = array('resultado' => '1');
            } 
          
            //convierto en json y retorno
            return new JsonResponse($resultado);
        }
    }
    
    /**
    * @Route("/rechazar_s",name="rechazar_s")     
    * @Method({"GET"}) 
    */
    public function rechazarSolicitud(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el id
            $id = $request->query->get('id');
            $motivo = $request->query->get('motivo');
            $comentarios = $request->query->get('comentarios');
            //var_dump($id);
            
            //consulto
            $db = $this->getDoctrine()->getEntityManager();
            $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($id);
            $estados = $db->getRepository('ComensalesBundle:TipoEstado')->findBynombreEstado('Rechazado');
            $tipos = $db->getRepository('ComensalesBundle:TipoRechazo')->findBynombreRechazo($motivo);
            
            
            //var_dump($solicitud);
            
            //actualizo
            //si esta vacio no se encontro
            //se retornara por json un valor 0 que indica que no fue encontrado
            //se retorna 1 si se encontro y borro
            if(empty($solicitud) || empty($estados) || empty($tipos))
            {
                $resultado = array ('resultado ' => '0');
            }
            else 
            {
                //seteo
                //$Solicitud = $solicitudes;
                $Estado = $estados[0];
                $Tipo = $tipos[0];
                
                //obtengo y seteo el motivo de rechazo
                $rechazoSolicitud = new MotivoRechazo();
                $rechazoSolicitud->setComentario($comentarios);
                $rechazoSolicitud->setTipoRechazo($Tipo);
                //cambio el estado y actualizo
                $solicitud->setTipoEstado($Estado);
                $solicitud->setMotivoRechazo($rechazoSolicitud);
                ////LA FECHA DE REVISION ES COLOCADA CON UN TRIGGER llamado setearFechaRevision
                $solicitud->setAutorizadoPor("@admin");
                //persisto el motivo de rechazo
                $db->persist($rechazoSolicitud);
                $db->flush();
                
                $resultado = array('resultado' => '1');
            } 
          
            //convierto en json y retorno
            return new JsonResponse($resultado);
        }
    }

    /**
    * @Route("/mas_s",name="mas_s")     
    * @Method({"GET"}) 
    */
    public function masInfo(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $id = $request->query->get('id');
            //var_dump($id);
            
            $db = $this->getDoctrine()->getEntityManager();
            //$solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($id);
            
            //return new JsonResponse($solicitud);
            $qb = $db->createQueryBuilder();
            
            $qb->select('p.correo,c.nombre,f.nombreFacultad,s.autorizadoPor,'
                    . 's.fechaRevision,enf.celiaco,enf.vegetariano')
               ->from('ComensalesBundle:Solicitud','s')
               ->innerJoin('s.persona','p')
               ->innerJoin('p.facultad','f')
               ->innerJoin('p.carrera','c')
               ->innerJoin('s.foto','fo')
               ->innerJoin('s.enfermedad','enf')
               ->where('s.id = :id_elegido')
               ->setParameter('id_elegido',$id)
            ;
            //consulto
            //$resultado = $q->getResult();
            $resultado = $qb->getQuery()->getArrayResult();
            
            //recupero la imagen en base64
            //accedo al elemento [0] y luego seteo [fotobase64]
            $resultado[0]["fotoBase64"] = $this->obtenerFotoBase64($id);
            
            //var_dump($resultado);

            //convierto en json y retorno
            return new JsonResponse($resultado);
        }
    }

    //to-do mover repositorio de fotos
    private function obtenerFotoBase64($idSolicitud)
    {
        //consulto
        $db = $this->getDoctrine()->getEntityManager();
        $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($idSolicitud);
        
       
        //obtengo la foto
        $foto = $solicitud->getFoto();
        
        $base64 = null;
        
        //pregunto si tiene foto
        if($foto->getPeso() != 0)
        {
            //obtengo la foto
            $imagenData = file_get_contents($foto->getNombreFisico());
        
            //codifico
            $base64 = base64_encode($imagenData);
        }
        
        //retorno
        return $base64;
    }
    
    
    /**
    * @Route("/eliminar_s",name="eliminar_s")     
    * @Method({"GET"}) 
    */
    public function eliminarSolicitud(Request $request)
    {
        //
        if($request->isXmlHttpRequest())
        {      
            //leo
            $nroSolicitud = $request->query->get('nroSolicitud');
            
            //consulto
            $db = $this->getDoctrine()->getEntityManager();
            $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($nroSolicitud);
            //var_dump($solicitud);
            //si esta vacio no se encontro
            //se retornara por json un valor 0 que indica que no fue encontrado
            //se retorna 1 si se encontro y borro
            if(empty($solicitud))
            {
                $resultado = array ('resultado ' => '0');
            }
            else 
            {
                //elimino
                $db->remove($solicitud);
                $db->flush();
                
                $resultado = array('resultado' => '1');
            }
          
            //convierto en json y retorno
            return new JsonResponse($resultado);
        }
    }
    
    private function obtenerTipo($tipo)
    {
        //por defecto el tipo es no definido
        $id = $this->getParameter('tipo_no_definido');
            
        if($tipo == 'Estudiante')
        {
            $id = $this->getParameter('tipo_estudiante_grado');
        }
        elseif($tipo == 'Docnodoc')
        {
            $id = $this->getParameter('tipo_docente_nodocente');
        }
        elseif($tipo == 'Invitado')
        {
            $id = $this->getParameter('tipo_invitado');
        }
        
        //retorno el id
        return $id;
    }
    
    private function obtenerEstado($estado)
    {
        //por defecto el estado es pendiente
        $id = $this->getParameter('estado_pendiente');
        
        if($estado == 'Aprobados')
        {
            $id = $this->getParameter('estado_aprobado'); 
        }
        elseif($estado == 'Rechazados')
        {
            $id =  $this->getParameter('estado_rechazado');
        }
        elseif($estado == 'Pendientes')
        {
               $id = $this->getParameter('estado_pendiente');
        }
        
        return $id;
    }
    
    private function obtenerFacultad($facultad)
    {
        //por defecto asumimos la facultad como 
        $id = $this->getParameter('facultad_no_definido');
        
        //selector
        switch($facultad)
        {
            case "fadu":
                $id = $this->getParameter('fadu');
                break;
            case "fbcb":
                $id = $this->getParameter('fbcb');
                break;
            case "fca":
                $id = $this->getParameter('fca');
                break;
            case "fce":
                $id = $this->getParameter('fce');
                break;
            case "fcv":
                $id = $this->getParameter('fcv');
                break;
            case "fca":
                $id = $this->getParameter('fca');
                break;
            case "fhuc":
                $id = $this->getParameter('fhuc');
                break;
            case "fiq":
                $id = $this->getParameter('fiq');
                break;
            case "fich":
                $id = $this->getParameter('fich');
                break;
            case "ess":
                $id = $this->getParameter('ess');
                break;
            case "ism":
                $id = $this->getParameter('ism');
                break;
            case "rec":
                $id = $this->getParameter('rectorado_secretarias');
                break;
            case "otras":
                $id = $this->getParameter('otras_insticuciones');
                break;
        }
        
        return $id;
    }
    
    /**
    * @Route("/actualizar_s",name="actualizar_s")     
    * @Method({"POST"}) 
    */
    public function actualizarSolicitud(Request $request)
    {
        //otra forma es actualizar una a una las tablas
        if($request->isXmlHttpRequest())
        {
            //leo todos los campos
            //OJO EN SYMFONY 3.X NO SE USA MAS REQUET->REQUEST->GET
            //SINO REQUEST->GET
            /* 
            $id = $request->request->get('id');
            $apellido = $request->request->get('apellido');
            $nombre = $request->request->get('nombre');
            $tipoComensal = $request->request->get('tipoComensal');
            $fecha = $request->request->get('datepicker');
            $horario = $request->request->get('horario_turno');
            $sede = $request->request->get('sede');
            $telefono = $request->request->get('telefono');
            $codtelefono = $request->request->get('codtelefono');
            $celiaco = $request->request->get('celiaco');
            $vegetariano = $request->request->get('vegetariano');
            $facultad = $request->request->get('facultad');
            $carrera = $request->request->get('carrera');
            */
            //
            //var_dump($_POST['array']);
            
            //          REFERENCIAS DE LO QUE VIENE EN listaActualizaciones
            // listaActualizaciones[0] = id de la solicitud
            // listaActualizaciones[1] = obj persona
            // listaActualizaciones[2] = datos de facultad
            // listaActualizaciones[3] = datos de carrera
            // listaActualizaciones[4] = obj foto
            // listaActualizaciones[5] = obj de turno
            // listaActualizaciones[6] = obj de detalle enfermedad
            // listaActualizaciones[7] = datos de tipo comensal
             
            $listaActualizaciones = json_decode($request->get('listaActualizaciones'));
            
            //var_dump($listaActualizaciones);
            //var_dump($listaActualizaciones[2]->dni);
            //var_dump(count($listaActualizaciones));
            if(count($listaActualizaciones) != 10)
            {
                throw $this->createNotFoundException('Error n° - no es posible actualizar la información');
            }
            //accedo a los elementos de la lista
            $idSolicitud = $listaActualizaciones[0];
            $dni = $listaActualizaciones[1];
            $persona = $listaActualizaciones[2];
            $facultad = $listaActualizaciones[3];
            $carrera = $listaActualizaciones[4];
            $file = $listaActualizaciones[5];
            $canvas = $listaActualizaciones[6];
            $turno = $listaActualizaciones[7];
            $detalleEnfermedad = $listaActualizaciones[8];
            $tipoComensal = $listaActualizaciones[9];
            
            return $this->actualizarTablasSelector($idSolicitud,$dni,$persona,$facultad,
                    $carrera,$file,$canvas,$turno,$detalleEnfermedad,$tipoComensal);
        }
    }
    
    private function actualizarTablasSelector($idSolicitud,$dni,$persona,$facultad,$carrera,$file,$canvas,$turno,$detalleEnfermedad,$tipoComensal)
    {
       
        //si debo actualizar la persona
            $this->actualizarPersona($persona);
        //si debo actualizar la facultad
            $this->actualizarFacultad($dni,$facultad);
        ////si debo actualizar la carrera
            $this->actualizarCarrera($dni, $nombreCarrera, $tipoComensal);
        ////si debo actualizar la foto
            $this->actualizarFoto($idSolicitud,$file,$canvas,$dni);
        ////si debo actualizar la detalle enfermedad
            $this->actualizarDetalle($idSolicitud, $detalleEnfermedad);
        //si debo actualizar el tipo de comensal
            $this->actualizarTipo($idSolicitud, $tipoComensal);
        return new JsonResponse(array('resultado' => '1'));
    }
    
    private function actualizarPersona($datos_persona)
    {
        //accedo a los elementos de $datos_persona
        $dni = $datos_persona->dni;
        $apellido = $datos_persona->apellido;
        $nombre = $datos_persona->nombre;
        $correo = $datos_persona->correo;
        $codtelefono = $datos_persona->codtelefono;
        $telefono = $datos_persona->telefono;
        
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $persona = $db->getRepository('ComensalesBundle:Persona')->findOneBydni($dni);
        //var_dump($dni);
        //var_dump($persona);
        if(!empty($persona))
        {
            //uso los sets
            $persona->setDni($dni);
            $persona->setApellido($apellido);
            $persona->setNombre($nombre);
            $persona->setCorreo($correo);
            $persona->setCodTelefono($codtelefono);
            $persona->setTelefono($telefono);
            
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar la persona');
        }
    }
    
    private function actualizarFacultad($dni,$datos_facultad)
    {
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $persona = $db->getRepository('ComensalesBundle:Persona')->findOneBydni($dni);
        //obtengo la referencia de la facultad
        //var_dump($datos_facultad);
        $referencia_facultad = $db->getReference('ComensalesBundle:Facultad',
                $this->obtenerFacultad($datos_facultad));
        
        if(!empty($persona) && !empty($referencia_facultad))
        {
            //uso los sets
            $persona->setFacultad($referencia_facultad);
            
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar la facultad');
        }
    }
    
    private function actualizarCarrera($dni,$nombreCarrera,$tipoComensal)
    {
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $persona = $db->getRepository('ComensalesBundle:Persona')->findOneBydni($dni);
        if($tipoComensal != "Estudiante de carrera de grado")
        {
            $nombreCarrera = 'NO DEFINIDO';
        }
        $carrera = $db->getRepository('ComensalesBundle:Carrera')->findOneBynombre($nombreCarrera);
        if(!empty($persona) && !empty($carrera))
        {
            //uso los sets
            $persona->setCarrera($carrera);
            
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar la carrera');
        }
        
    }
    
    private function actualizarFoto($idSolicitud,$file,$canvas,$dni)
    {
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($idSolicitud);
        $fotoVieja = $db->getRepository('ComensalesBundle:Foto')->find($solicitud->getFoto()->getId());
        
        if(!empty($solicitud))
        {
            //remuevo
            $db->remove($fotoVieja);
            
            $gestorFotos = new FotoController();
            $foto = $gestorFotos->seteoFoto($file,$canvas,$dni);
        
            //uso los sets
            $solicitud->setFoto($foto);
            
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar la foto');
        }
    }
    
    private function actualizarTurno($idSolicitud,$turno)
    {
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($idSolicitud);
        //creo una instancia de formulario para usar los metodos creados de
        //asignar turno y decrementar cupo
        $formulario = new FormularioController();
        $nuevoTurno = $formulario->asignarTurno($turno->datepicker, $turno->sede , $turno->horario_turno);
        //incremento
        $formulario->incrementarTurno($turno->datepicker, $turno->sede);
        //asigno el nuevo turno a la solicitud
        $solicitud->setTurno($nuevoTurno);
        //guardo
        $db->flush();
    }
    
    private function actualizarDetalle($idSolicitud,$detalle)
    {
        //var_dump($detalle->vegetariano);
        //var_dump($detalle->celiaco);
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($idSolicitud);
        $detalleViejo = $db->getRepository('ComensalesBundle:DetalleEnfermedad')->find($solicitud->getEnfermedad()->getId());
        if(!empty($solicitud) && !empty($detalle))
        {
                //seteo
            $detalleViejo->setVegetariano($detalle->vegetariano);
            $detalleViejo->setCeliaco($detalle->celiaco);
            
            //uso los sets
            $solicitud->setEnfermedad($detalleViejo);
            
            //$db->persist($detalleViejo);
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar el detalle');
        }
    }
    
    private function actualizarTipo($idSolicitud,$tipo)
    {
        //conecto con la base de datos
        $db = $this->getDoctrine()->getEntityManager();
        $solicitud = $db->getRepository('ComensalesBundle:Solicitud')->find($idSolicitud);
        //$referencia_tipo = $db->getReference('ComensalesBundle:TipoComensal',
        //        $this->obtenerTipo($tipo));
        $referencia_tipo = $db->getRepository('ComensalesBundle:TipoComensal')->findOneBynombreComensal($tipo);
        
        if(!empty($solicitud) && !empty($referencia_tipo))
        {
            //uso los sets
            $solicitud->setTipoComensal($referencia_tipo);
            
            //guardo
            $db->flush();
        }
        else
        {
            throw $this->createNotFoundException('Error n° - No fue posible actualizar el tipo de comensal');
        }
    }
    //FINAL DE LA CLASE
}

        


