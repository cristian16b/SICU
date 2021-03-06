<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComensalesBundle\Entity\Turno;

/**
 * Description of GestionTurnoController
 *
 * @author Cristian B
 */
class GestorTurnoController extends Controller
{
    protected $entityManager;
    //usamos injeccion de dependencias porque no puedo usar un servicio en otro
    //to-do ver como resolverlo
    protected $solicitudes;

    //referencia de inyeccion de dependencias
    //https://librosweb.es/libro/symfony_2_x/capitulo_16/inyectando_servicios.html
    //https://openwebinars.net/blog/symfony2-tutorial-servicios-e-inyeccion-de-dependencias/
    public function __construct($entityManager,$gestor_solicitudes)
    {
        $this->entityManager = $entityManager;
        $this->solicitudes = $gestor_solicitudes;
    }
   
    public function listar($sede,$fecha_elegida)
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        //escribo la consulta
        $qb->select('s.nombreSede,t.dia,t.horario,t.cupo')
           ->from('ComensalesBundle:Turno','t')
           ->innerJoin('t.sede','s')
           ->where('t.dia = :fecha_elegida')
           ->andWhere('s.nombreSede = :sede_elegida')
           ->setParameter('fecha_elegida',$fecha_elegida)
           ->setParameter('sede_elegida',$sede)
               ;
//        la siguiente linea permite obtener la consulta sql
        return new JsonResponse($qb->getQuery()->getArrayResult());
    }
    
    public function listarSolicitantesTurno($sede,$fecha,$horario)
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        $qb->select('p.dni,p.apellido,p.nombre,f.nombreFacultad,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('s.tipo_comensal','tc')
           ->innerJoin('p.facultad','f')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','se')
           ->where('t.dia = :fecha_elegida')
           ->andWhere('t.horario = :horario_elegido')
           ->andWhere('se.nombreSede = :sede_elegida')
           ->setParameter('fecha_elegida',$fecha)
           ->setParameter('sede_elegida',$sede)
           ->setParameter('horario_elegido',$horario)
                ;
        
        return new JsonResponse($qb->getQuery()->getArrayResult());
    }

    public function crearTurno($nombreSede,$fecha,$horaInicio,$horaFin,$cupo)
    {
        $db = $this->entityManager;
        $sede = $db->getRepository('ComensalesBundle:Sede')->findBynombreSede($nombreSede);
        
        if(empty($sede))
        {
            $resultado = array ('resultado ' => '0');
        }
        else
        {
            $hora = $horaInicio;
            $horaFin = $horaFin - 1;
            $listaTurnos = array();
            $i=0;
            for($hora = $horaInicio; $hora < $horaFin; $hora++)
            {
                $turnoNuevo = new Turno();
                $turnoNuevo->setCupo((int)$cupo);
                $turnoNuevo->setSede($sede[0]);
                $fechaIngresada = \DateTime::createFromFormat('Y-m-j', $fecha);
                $turnoNuevo->setDia($fechaIngresada);
                $turnoNuevo->setHorario($hora);
                $listaTurnos[$i] = $turnoNuevo;
                $i++;
            }
            for($i=0;$i<count($listaTurnos);$i++)
            {
                $db->persist($listaTurnos[$i]);
            }
            $db->flush();
            $resultado = array ('resultado ' => '1');
        }
        return new JsonResponse($resultado);
    }
    
    public function modificarCupo($sede,$fecha,$horario,$cantidad)
    {
        $long = count($horario);
        $retorno = null;
        if($long == 1)
        {
           $retorno = $this->modificarUnCupo($sede, $fecha, $horario, $cantidad);
        }
        else if($long > 1)
        {
            $retorno = $this->modificarVariosCupos($sede, $fecha, $horario, $cantidad);
        }
        return $retorno;
    }
    
    private function modificarUnCupo($sede,$fecha,$horario,$cantidad)
    {
        $resultado = $this->obtenerConsultaModifCupo($fecha, $sede, $horario);
        
        if(empty($resultado))
        {
            throw $this->createNotFoundException('Error n° - Turno no encontrado');
        }
        
        if($resultado[0]->getCupo() + $cantidad  < 0)
        {
            throw $this->createNotFoundException('Error n° - El cupo no puede ser negativo');
        }
        
        //sete
        $resultado[0]->setCupo($resultado[0]->getCupo()+$cantidad);
        //guardo
        $this->entityManager->flush();
        
        return new JsonResponse(array ('resultado ' => '1'));
    }
    
    private function modificarVariosCupos($sede,$fecha,$listaHorarios,$cantidad)
    {
        $cantidadElem = count($listaHorarios);
        for($i=0;$i<$cantidadElem;$i++)
        {
            $this->modificarUnCupo($sede, $fecha, $listaHorarios[$i], $cantidad);
        }
//        die;
        return new JsonResponse(array ('resultado ' => '1'));
    }
    
    private function obtenerConsultaModifCupo($fecha,$sede,$horario)
    {
        return $this->entityManager->createQueryBuilder()->select('t')
               ->from('ComensalesBundle:Turno','t')
               ->innerJoin('ComensalesBundle:Sede','s')
               ->where('t.dia = :fecha')
               ->andWhere('s.nombreSede = :sede')
               ->andWhere('t.horario = :horario')
               ->setParameter('fecha',$fecha)
               ->setParameter('sede',$sede)
               ->setParameter('horario',$horario)
               ->getQuery()->getResult();
    }
    
    public function eliminarHorarios($sede,$fecha,$listaHorarios)
    {
        $cantidadElem = count($listaHorarios);
        for($i=0;$i<$cantidadElem;$i++)
        {
            $resultado = $this->obtenerHorario($fecha, $sede, $listaHorarios[$i]);
            if($resultado[0]->getCupo() !== 0)
            {
                $resultado[0]->setCupo(0);
                $this->entityManager->flush();
            }
        }
        return new JsonResponse(array('resultado' => '1'));
    }
    
    private function obtenerHorario($fecha,$sede,$horario)
    {
//        var_dump($fecha);
        //escribo la consuLta
        $resultado = 
           $this->entityManager->createQueryBuilder()
           ->select('t')
           ->from('ComensalesBundle:Turno','t')
           ->innerJoin('ComensalesBundle:Sede','s')
           ->where('t.dia = :fecha_elegida')
           ->andWhere('s.nombreSede = :sede_elegida')
           ->andWhere('t.horario = :horario_elegido')
           ->setParameter('fecha_elegida',$fecha)
           ->setParameter('sede_elegida',$sede)
           ->setParameter('horario_elegido',$horario)
           ->getQuery()->getResult()
           ;
        if(empty($resultado))
        {
            throw $this->createNotFoundException('Error n° - Turno no encontrado');
        }
        //retorno
        return $resultado;
    }
    
    public function eliminarSolicitanteHorarios($listaSolicitantes)
    {
        $cantidadElem = count($listaSolicitantes);
        for($i=0;$i<$cantidadElem;$i++)
        {
            //accedo al servicio 
//            $servicio = $this->container->get('gestor_solicitudes');
            $servicio = $this->solicitudes;
            //
            $solicitud = $servicio->obtenerSolicitudActual($listaSolicitantes[$i]);
//            var_dump($solicitud);die;
            $turnoAsignado = $solicitud[0]->getTurno();
//            var_dump($turnoAsignado);die;
            if($turnoAsignado != NULL)
            {
                $sede = $turnoAsignado->getSede()->getNombreSede();
                $fecha = $turnoAsignado->getDia();
                $horario = $turnoAsignado->getHorario();
                
                $this->modificarUnCupo($sede,
                                       $fecha, 
                                       $horario, 
                                       1);
                //asigno turno null
                $solicitud[0]->setTurno(NULL);
                $this->entityManager->flush();
            }
        }
        return new JsonResponse(array('resultado' => '1'));
    }
    
    public function obtenerHorarios($sede,$fecha)
    {
        //escribo la consuLta
        $resultado = 
           $this->entityManager->createQueryBuilder()
           ->select('t')
           ->from('ComensalesBundle:Turno','t')
           ->innerJoin('ComensalesBundle:Sede','s')
           ->where('t.dia = :fecha_elegida')
           ->andWhere('s.nombreSede = :sede_elegida')
           ->setParameter('fecha_elegida',$fecha)
           ->setParameter('sede_elegida',$sede)
           ->getQuery()
           ->getArrayResult()
           ;
        if(empty($resultado))
        {
            throw $this->createNotFoundException('Error n° - Turno no encontrado');
        }
        //retorno
        return new JsonResponse($resultado);
    }
    
    public function cambiarTurno($sede,$fecha,$horario,$dni)
    {
 //       $servicio = $this->container->get('gestor_solicitudes');
        $servicio = $this->solicitudes;
        //accedo al servicio 
        $solicitud = $servicio->obtenerSolicitudActual($dni);
            
        $turnoAsignado = $solicitud[0]->getTurno();
        if($turnoAsignado != NULL)
        {
            $sedeAnterior = $turnoAsignado->getSede()->getNombreSede();
            $fechaAnterior = $turnoAsignado->getDia();
            $horarioAnterior = $turnoAsignado->getHorario();

            $this->modificarUnCupo($sedeAnterior,
                                   $fechaAnterior, 
                                   $horarioAnterior, 
                                   1);
//            //creo el nuevo turno
            $nuevoTurno = $this->obtenerHorario($fecha, $sede, $horario);
            $solicitud[0]->setTurno($nuevoTurno[0]);
            $this->modificarUnCupo($sede, $fecha, $horario, -1);
            $this->entityManager->flush();
        }
        return new JsonResponse(array('resultado' => '1'));
    }
    
    public function buscarTurnoDni($dni)
    {
        $fecha = new \DateTime();
        $fecha->setDate(date("Y"), 1,1);
        $resultado =
                $this->entityManager->createQueryBuilder()
           ->select('p.dni,p.apellido,p.nombre,sede.nombreSede,t.dia,t.horario,tc.nombreComensal')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->innerJoin('s.turno','t')
           ->innerJoin('t.sede','sede')
           ->innerJoin('s.tipo_comensal','tc')
           ->where('p.dni = :dni')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('dni',$dni)
           ->setParameter('fecha',$fecha)
           ->getQuery()->getArrayResult()
            ;
        if(empty($resultado))
        {
            throw $this->createNotFoundException('Error n° - Solicitud no encontrado');
        }
        
        return new JsonResponse($resultado);
    }

    public function asignarTurno($sede,$fecha,$horario)
    {
        //to-do pasar la func de solicitud a este servicio
    }        
            
}
