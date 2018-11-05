<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComensalesBundle\Entity\Sede;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use ComensalesBundle\Entity\Turno;

/**
 * Description of GestionTurnoController
 *
 * @author Cristian B
 */
class GestorTurnoController extends Controller
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerSedes()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('s.id,s.nombreSede')
           ->from('ComensalesBundle:Sede','s')
           ->orderBy('s.nombreSede','ASC')
        ;
        return $qb->getQuery()->getArrayResult();
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
//        $sql = $qb->getQuery()->getSql();
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
//        var_dump($nombreSede);die;
        $db = $this->entityManager;
        $sede = $db->getRepository('ComensalesBundle:Sede')->findBynombreSede($nombreSede);
//        var_dump($sede);
        
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
//        var_dump($resultado[0]->getCupo());
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
//        var_dump($listaSolicitantes);die;
        $cantidadElem = count($listaSolicitantes);
        for($i=0;$i<$cantidadElem;$i++)
        {
            //accedo al servicio 
            $servicio = $this->container->get('gestor_solicitudes');
            //
            $solicitud = $servicio->obtenerSolicitud($listaSolicitantes[$i]);
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
//        var_dump($sede . $fecha);
        //escribo la consuLta
        //TODO revisar consulta
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
}
