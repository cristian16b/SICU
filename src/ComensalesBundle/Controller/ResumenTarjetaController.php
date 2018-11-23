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
 * Description of ResumenTarjetaController
 * En esta clase, se obtienen el resumen informativo sobre 
 * + cantidad de tarjetas
 * + saldo positivo ( a favor de los comensales)
 * + saldo negativo ( deudas de los comensales)
 * @author Cristian B
 */
class ResumenTarjetaController extends Controller{
    
   /**
   * @Route("tarjetas/resumen",name="tarjetas_resumen")     
   * @Method({"GET"}) 
   */
   public function obtenerResumenInformativo(Request $request)
   {
       $organismo = $request->query->get('organismo');
       
       $resultado = null;
       if(isset($organismo))
       {
            $resultado = array(
                    $this->obtenerCantidad($organismo),
                    $this->obtenerSaldoPositivo($organismo),
                    $this->obtenerSaldoNegativo($organismo)
            );
       }
       return new JsonResponse($resultado);
   }
   
   private function obtenerSaldoPositivo($organismo)
   {
       $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('sum(tarj.saldo) as saldoPositivo')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('tarj.saldo > 0')
                   ->setParameter('organismo',$organismo)
            ;
        return $qb->getQuery()->getSingleResult();
   }
   
   private function obtenerSaldoNegativo($organismo)
   {
       $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('sum(tarj.saldo) as saldoNegativo')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->andWhere('tarj.saldo <= 0')
                   ->setParameter('organismo',$organismo)
            ;
        return $qb->getQuery()->getSingleResult();
   }
   
   private function obtenerCantidad($organismo)
   {
       $qb = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('count(tarj) as cantidad')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('per.facultad','facu')
                   ->where('facu.nombreFacultad = :organismo')
                   ->setParameter('organismo',$organismo)
            ;
        return $qb->getQuery()->getSingleResult();
   }
}
