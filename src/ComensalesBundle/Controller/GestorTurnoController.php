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
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();

        //escribo la consuLta
        $qb->select('t')
               ->from('ComensalesBundle:Turno','t')
               ->where('t.dia = :fecha')
               ->andWhere('t.sede = :sede')
               ->setParameter('fecha',$fecha)
               ->setParameter('sede',$sede)
               ->setParameter('horario',$horario)
               ;
        $q = $qb->getQuery();
        //consulto
        $resultado = $q->getResult();
        //decremento
        if(empty($resultado))
        {
            throw $this->createNotFoundException('Error n° - Turno no encontrado');
        }
            //pregunto 
        if($resultado[0]->getCupo() + $cantidad  < 0)
        {
            throw $this->createNotFoundException('Error n° - El cupo no puede ser negativo');
        }
        //sete
        $resultado[0]->setCupo($resultado[0]->getCupo()+$cantidad);
        //guardo
        $db->flush();
        return $resultado[0];        
    }
    ////////////////////////////////////////////////////////////////////////
    
    public function obtenerHorarios($sede,$dia,$mes,$año)
    {
        //consulta dql con doctrine
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();

        //escribo la consuLta
        $qb->select('t.horario,t.cupo')
           ->from('ComensalesBundle:Turno','t')
           ->innerJoin('ComensalesBundle:Sede','s')
           ->where('t.dia = :fecha_elegida')
           ->andWhere('s.nombreSede = :sede_elegida')
           ->setParameter('fecha_elegida',$fecha)
           ->setParameter('sede_elegida',$sede)
           ;
        //genero
        $q = $qb->getQuery();
        //consulto
        //$resultado = $q->getResult();
        $resultado = $q->getArrayResult();
        //retorno
        return new JsonResponse($resultado);
    }
    
    
    public function eliminarTurno($sede,$fecha,$horario)
    {
        
    }
    
    //retorna un objeto del tipo solicitud
    public function obtenerTurnoSolicitud($dni,$nroSolicitud)
    {
        /*
        $db = $this->getDoctrine()->getEntityManager();
        $qb = $db->createQueryBuilder();
        $qb->select('t.horario,t.cupo')
           ->     
        */
        
    }
    
    public function cambiarTurno($sede_anterior,$fecha_anterior,$horario_anterior,
                                 $sede_nueva,$fecha_nueva,$horario_nuevo,$dni,$nroSolicitud)
    {
        
    }
}
