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
    * @Route("/listar-tarjetas",name="listar_tarjetas")     
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
   
   private function listarSaldoNegativo($organismo)
   {
       
   }
   
   private function listarSaldoPositivo($organismo)
   {
       
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
       
   }
   
   private function listarCanceladas($organismo)
   {
       
   }
   
}
