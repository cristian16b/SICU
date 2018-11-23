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

/**
 * Description of ListarTarjetaController
 * @author Cristian B
 */
class ListarTarjetaController extends Controller{
   
   /**
   * @Route("tarjetas/filtrar",name="tarjetas_filtrar")     
   * @Method({"GET"}) 
   */
   public function listar(Request $request)
   {
       if($request->isXmlHttpRequest())
       {
            $tipoFiltro = $request->query->get('tipoFiltro');
            $organismo  = $request->query->get('organismo');
            
            if(isset($organismo) && isset($tipoFiltro))
            {
                return  $this->selectorFiltros($organismo,$tipoFiltro);
            }
       }
       return null;
   }
   
   /**
   * @Route("tarjetas/buscar",name="tarjetas_buscar")     
   * @Method({"GET"}) 
   */
   public function buscar(Request $request)
   {
       if($request->isXmlHttpRequest())
       {
            $datoIngresado = $request->query->get('datoIngresado');
            $tipoFiltro  = $request->query->get('tipoFiltro');
            
            if(isset($datoIngresado) && isset($tipoFiltro))
            {
                return  $this->selectorBusqueda($datoIngresado,$tipoFiltro);
            }
       }
       return null;
   }
   
   private function selectorFiltros($organismo,$tipoFiltro)
   {
       $salida = Null;
       switch ($tipoFiltro)
       {
           CASE "Saldo negativo":
               $salida = $this->listarSaldoNegativo($organismo);
               break;
           CASE "Saldo positivo":
               $salida = $this->listarSaldoPositivo($organismo);
               break;
           CASE "No entregadas":
               $salida = $this->listarNoEntregadas($organismo);
               break;
           CASE "Activas":
               $salida = $this->listarActivas($organismo);
               break;
           CASE "Canceladas":
               $salida = $this->listarCanceladas($organismo);
               break;
       }
       return $salida;
   }
   
   private function selectorBusqueda($dato,$tipoFiltro)
   {
       $salida = Null;
       switch ($tipoFiltro)
       {
           CASE "Nro Tarjeta":
               $salida = $this->buscarCodigo($dato);
               break;
           CASE "DNI":
               $salida = $this->buscarDni($dato);
               break;
       }
       return $salida;
   }
   
   private function listarSaldoNegativo($organismo)
   {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('tarj.saldo <= 0')
                   ->setParameter('organismo',$organismo)
                   ->orderBy('per.apellido','ASC')
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function listarSaldoPositivo($organismo)
   {
       $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('tarj.saldo > 0')
                   ->setParameter('organismo',$organismo)
                   ->orderBy('per.apellido','ASC')
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function listarNoEntregadas($organismo)
   {
        $estado = 'Pendiente';
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('est.nombreEstadoTarjeta = :estado')
                   ->setParameter('organismo',$organismo)
                   ->setParameter('estado',$estado)
                   ->orderBy('per.apellido','ASC')
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function listarActivas($organismo)
   {
       $estado = 'Activa';
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('est.nombreEstadoTarjeta = :estado')
                   ->setParameter('organismo',$organismo)
                   ->setParameter('estado',$estado)
                   ->orderBy('per.apellido','ASC')
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function listarCanceladas($organismo)
   {
       $estado = 'Cancelada';
       $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('est.nombreEstadoTarjeta = :estado')
                   ->setParameter('organismo',$organismo)
                   ->setParameter('estado',$estado)
                   ->orderBy('per.apellido','ASC')
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function buscarDni($dato)
   {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('per.dni = :dni')
                   ->setParameter('dni',$dato)
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
   
   private function buscarCodigo($dato)
   {
        $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,tarj.fechaAlta,tarj.saldo,est.nombreEstadoTarjeta,'
                           . 'per.nombre,per.apellido,per.dni')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('tarj.id = :id')
                   ->setParameter('id',$dato)
                ;
        return new JsonResponse($qb->getQuery()->getArrayResult());
   }
}
