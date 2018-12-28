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
            $tipo = $this->obtenerTipo($request->query->get('tipo'));
            $facultad = $request->query->get('facultad');
            $estado = $this->obtenerEstado($request->query->get('estado'));
            //obtengo la fecha actual
            //genero una fecha generica (1-1-año actual)
            $fecha = new \DateTime();
            $fecha->setDate(date("Y"), 1,1);
            //me conecto con la base de datos
            $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            
            //pregunto que consulta debo ejecutar
            if($tipo != 'Todos' && $estado != 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 1');
                $qb = $this->obtenerConsultaI($qb,$fecha,$estado, $facultad, $tipo);
            }
            elseif($tipo == 'Todos' && $estado != 'Todos')
            {
                //throw $this->createNotFoundException($tipo == '4' && $estado != '4');
                $qb = $this->obtenerConsultaII($qb, $fecha, $facultad, $estado);
            }
            elseif($tipo != 'Todos' && $estado == 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 3');
                $qb = $this->obtenerConsultaIII($qb, $fecha, $tipo, $facultad);
            }
            elseif($tipo == 'Todos' && $estado == 'Todos')
            {
                //throw $this->createNotFoundException('entra en la 4');
                $qb = $this->obtenerConsultaIV($qb, $fecha, $facultad);
            }

            //convierto en json y retorno
            return new JsonResponse($qb->getQuery()->getArrayResult());
        }
    }
    
    
    /*
     * esta funcion hace un mapeo entre los valores recibidos del select
     * con los estados de la tabla tipo estado de la solicitud
     * to-do reemplazar el formulario los value por los valores de la bd
     * y con eso eliminar este mapeo
     */
    private function obtenerEstado($estado)
    {
        $salida = 'Todos';
        if($estado == 'Aprobados')
        {
            $salida = 'Aceptado';
        }
        else if($estado == 'Rechazados')
        {
            $salida = 'Rechazado';
        }
        else if($estado == 'Pendientes')
        {
            $salida = 'Pendiente';
        }
        return $salida;
    }
    private function obtenerTipo($tipo)
    {
        $salida = 'Todos';
        if($tipo == 'Estudiante')
        {
            $salida = 'Estudiante de carrera de grado';
        }
        else if($tipo == 'Docnodoc')
        {
            $salida = 'Docente - No docente';
        }
        else if($tipo == 'Invitado')
        {
            $salida = 'Invitado';
        }
        return $salida;
    }


    /*
     * Obtener la consulta sql que sera ejecutada posteriormente
       MOSTRAR <estudiante|doc-nonoc|invitado>
       MOSTRAR <facultad elegida>
       MOSTRAR <aceptado|rechazado|pendiente>
     * 
     */
    private function obtenerConsultaI($qb,$fecha,$estado,$facultad,$tipo)
    {
        //consulto
        return $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('f.nombreCortoFacultad = :facultad_elegida')
           ->andWhere('e.nombreEstado = :estado_elegido')
           ->andWhere('tc.nombreComensal = :comensal_elegido')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$facultad)
           ->setParameter('estado_elegido',$estado)
           ->setParameter('comensal_elegido',$tipo)         
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
    }
    
    /*Obtener la consulta sql que sera ejecutada posteriormente
     * MOSTRAR <todos>
       MOSTRAR <facultad elegida>
       MOSTRAR <aceptado|rechazado|pendiente>
     * 
     */
    private function obtenerConsultaII($qb,$fecha,$facultad,$estado)
    {
        //consulto
        return   $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('f.nombreCortoFacultad = :facultad_elegida')
           ->andWhere('e.nombreEstado = :estado_elegido')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$facultad)
           ->setParameter('estado_elegido',$estado)
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
    }
    
    /*
     * //TERCER CASO:
        MOSTRAR <estudiante|doc-nodoc|invitado>
        MOSTRAR <facultad elegida>
        MOSTRAR <todos>
     */
    private function obtenerConsultaIII($qb,$fecha,$tipo,$facultad)
    {
        //consulto
        return $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('s.tipo_comensal','tc')
           ->innerJoin('t.sede','se')
           ->where('f.nombreCortoFacultad = :facultad_elegida')
           ->andWhere('tc.nombreComensal = :comensal_elegido')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$facultad)
           ->setParameter('comensal_elegido',$tipo)         
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
    }
    
    
    /*
     * //CUARTO CASO:
        MOSTRAR <todos>
        MOSTRAR <facultad elegida>
        MOSTRAR <todos>
     */
    private function obtenerConsultaIV($qb,$fecha,$facultad)
    {   
        //consulto
        return  $qb->select('s.id,p.dni,p.apellido,p.nombre,p.codTelefono,p.telefono,se.nombreSede,'
                . 't.horario,t.dia,e.nombreEstado,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.tipo_estado','e')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('f.nombreCortoFacultad = :facultad_elegida')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('facultad_elegida',$facultad)
           ->setParameter('fecha',$fecha)
           ->orderBy('p.apellido','ASC')
        ;
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
            
            $qb =  $this->getDoctrine()->getEntityManager()->createQueryBuilder();
            
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

            //convierto en json y retorno
            return new JsonResponse( $qb->getQuery()->getArrayResult());
            
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