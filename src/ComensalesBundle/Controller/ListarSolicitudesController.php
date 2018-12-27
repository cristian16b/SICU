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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Description of FiltrarSolicitudesController
 *
 * @author Cristian B
 */
class ListarSolicitudesController  extends Controller{
    /**
    * @Route("/filtrar_s",name="filtrar_s")     
    * @Method({"GET"}) 
    * @Security("has_role('ROLE_ADMINISTRATIVO')") 
    */
    public function filtrarListado(Request $request)
    {    
        if($request->isXmlHttpRequest())
        {
            //leo los datos enviados
            $tipo = $request->query->get('tipo');
            $facultad = $request->query->get('facultad');
            $estado = $request->query->get('estado');
            
            
            //obtengo el id de facultad/estado/tipo

            $id_facultad = $this->getDoctrine()
                                ->getRepository('ComensalesBundle:Facultad')
                                ->obtenerFacultadId($facultad);
            $id_estado = $this->getDoctrine()
                              ->getRepository('ComensalesBundle:TipoEstado')
                              ->obtenerEstadoSolicitudId($estado);
            $id_tipo = $this->getDoctrine()
                            ->getRepository('ComensalesBundle:TipoComensal')
                            ->obtenerTipoComensalId($tipo);    
            //obtengo la fecha actual
            //genero una fecha generica (1-1-año actual)
            $fecha = new \DateTime();
            $fecha->setDate(date("Y"), 1,1);
            
            //me conecto con la base de datos
            $db = $this->getDoctrine()->getEntityManager();
            $qb = $db->createQueryBuilder();
            
            //pregunto que consulta debo ejecutar
            if($tipo != 'Todos' && $estado != 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 1');
                $qb = $this->obtenerConsultaI($qb,$fecha,$id_estado, $id_facultad, $id_tipo);
            }
            elseif($tipo == 'Todos' && $estado != 'Todos')
            {
                //throw $this->createNotFoundException($tipo == '4' && $estado != '4');
                $qb = $this->obtenerConsultaII($qb, $fecha, $id_facultad, $id_estado);
            }
            elseif($tipo != 'Todos' && $estado == 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 3');
                $qb = $this->obtenerConsultaIII($qb, $fecha, $id_tipo, $id_facultad);
            }
            elseif($tipo == 'Todos' && $estado == 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 4');
                $qb = $this->obtenerConsultaIV($qb, $fecha, $id_facultad);
            }

            //convierto en json y retorno
            return new JsonResponse($qb->getQuery()->getArrayResult());
        }
    }
        
    /*
     * Obtener la consulta sql que sera ejecutada posteriormente
       MOSTRAR <estudiante|doc-nonoc|invitado>
       MOSTRAR <facultad elegida>
       MOSTRAR <aceptado|rechazado|pendiente>
     * 
     */
    private function obtenerConsultaI($qb,$fecha,$id_estado,$id_facultad,$id_tipo)
    {
        //consulto
        $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('p.facultad = :facultad_elegida')
           ->andWhere('s.tipo_estado = :estado_elegido')
           ->andWhere('s.tipo_comensal = :comensal_elegido')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$id_facultad)
           ->setParameter('estado_elegido',$id_estado)
           ->setParameter('comensal_elegido',$id_tipo)         
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
        
        //retorno
        return $qb;
    }
    
    /*Obtener la consulta sql que sera ejecutada posteriormente
     * MOSTRAR <todos>
       MOSTRAR <facultad elegida>
       MOSTRAR <aceptado|rechazado|pendiente>
     * 
     */
    private function obtenerConsultaII($qb,$fecha,$id_facultad,$id_estado)
    {
        //consulto
        $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('p.facultad = :facultad_elegida')
//           ->andWhere('s.tipo_estado = :estado_elegido')
//           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$id_facultad)
//           ->setParameter('estado_elegido',$id_estado)
//           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;

        //retorno
        return $qb;
        
    }
    
    /*
     * //TERCER CASO:
        MOSTRAR <estudiante|doc-nodoc|invitado>
        MOSTRAR <facultad elegida>
        MOSTRAR <todos>
     */
    private function obtenerConsultaIII($qb,$fecha,$id_tipo,$id_facultad)
    {
        //consulto
        $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('s.tipo_comensal','tc')
           ->innerJoin('t.sede','se')
           ->where('p.facultad = :facultad_elegida')
           ->andWhere('s.tipo_comensal = :comensal_elegido')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$id_facultad)
           ->setParameter('comensal_elegido',$id_tipo)         
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
        
        
        //retorno
        return $qb;    
    }
    
    /*
     * //CUARTO CASO:
        MOSTRAR <todos>
        MOSTRAR <facultad elegida>
        MOSTRAR <todos>
     */
    private function obtenerConsultaIV($qb,$fecha,$id_facultad)
    {
        //consulto
        $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('f.nombreCortoFacultad = :facultad_elegida')
//           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$id_facultad)
//           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
        //retorno
        return $qb;
    }
    
    
    /**
    * @Route("/buscar_s",name="buscar_s")     
    * @Method({"GET"}) 
    */
    public function buscarComensal(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $filtro = $request->query->get('filtro');
            $abuscar = $request->query->get('abuscar');
            
            $db = $this->getDoctrine()->getEntityManager();
            $qb = $db->createQueryBuilder();
            
            //pregunto
            if($filtro == 'Apellido')
            {
               $qb = $this->consultarApellido($qb,$abuscar);
            }
            elseif($filtro == 'Dni')
            {
               $qb = $this->consultarDni($qb,$abuscar);
            }
            else
            {
                throw $this->createNotFoundException('ERROR: Fallo la busqueda del comensal');
            }
            
            $q = $qb->getQuery();
            
            //consulto
            //$resultado = $q->getResult();
            $resultado = $q->getArrayResult();

            //convierto en json y retorno
            return new JsonResponse($resultado);
            
        }
    }
    
    
    
    private function consultarApellido($qb,$apellido)
    {
             //consulto
        return  $qb->select('s.id,p.dni,p.apellido,p.nombre,p.correo,'
                . 'p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('p.apellido = :apellido_elegido')
           ->setParameter('apellido_elegido',$apellido)
           ->orderBy('p.apellido','ASC')
        ;
    }
    
    private function consultarDni($qb,$dni)
    {
             //consulto
        return $qb->select('s.id,p.dni,p.apellido,p.nombre,p.correo,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('p.dni = :dni_elegido')
           ->setParameter('dni_elegido',$dni)
           ->orderBy('p.apellido','ASC')
        ;
    }
    
    
    
}
