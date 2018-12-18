<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Description of HistorialTarjetaController
 * @author Cristian B
 */
class HistorialCRController extends Controller
{
    private $entityManager;


    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    //to-do terminar este metodo en una refactorizacion posterior
    //por el momento no es relevante
    public function obtenerHistorialTarjeta($tarjeta)
    {
        $retorno = null;
        if(!empty($tarjeta))
        {
            $id = $tarjeta->getId();
            //armo el historial de consumo y recarga
            //consulto
            $db = $this->entityManager;
            $consumos = $this->obtenerConsumos($id);
            $recargas = $this->obtenerRecargas($id);
            $merge = array_merge($consumos,$recargas);
            $retorno = $merge;
        }
        
        return new JsonResponse($retorno);
    }
    
    public function obtenerConsumos($id,$anio)
    {
        //variable auxiliar para comparar fecha
        //genero una fecha generica (1-1-año actual)
        $fecha = new \DateTime();
        $fecha->setDate($anio, 1,1);
        
        return $this->entityManager->createQueryBuilder()
                    ->select('tarj.id as id,'
                            . 'hc.fechaHoraConsumo as fecha,'
                            . 'sed.nombreSede as sede,'
                            . 'item.nombreItemConsumo as concepto,'
                            . 'imp.precio as importe,'
                            . 'tarj.saldo')
                    ->from('ComensalesBundle:HistorialConsumos','hc')
                    ->innerJoin('hc.tarjeta','tarj')
                    ->innerJoin('hc.itemConsumo','item')
                    ->innerJoin('item.importe','imp')
                    ->innerJoin('hc.sedeConsumo','sed')
                    ->where('tarj.id = :id')
                    ->andWhere('hc.fechaHoraConsumo > :fechaActual')
                    ->setParameter('id',$id)
                    ->setParameter('fechaActual',$fecha)
                    ->getQuery()
                    ->getArrayResult();
    }
    
    public function obtenerRecargas($id,$anio)
    {
        //variable auxiliar para comparar fecha
        //genero una fecha generica (1-1-añio)
        $fecha = new \DateTime();
        $fecha->setDate($anio, 1,1);
        
        return     
           $this->entityManager->createQueryBuilder()
                ->select('hr.fechaHoraRecarga as fecha,'
                        . 'hr.montoRecarga as importe,'
                        . 'sed.nombreSede as sede,'
                        . 'item.nombreItemRecarga as concepto,'
                        . 'tarj.saldo')
                ->from('ComensalesBundle:HistorialRecargas','hr')
                ->innerJoin('hr.tarjeta','tarj')
                ->innerJoin('hr.itemRecarga','item')
                ->innerJoin('hr.sedeRecarga','sed')
                ->where('tarj.id = :id')
                ->andWhere('hr.fechaHoraRecarga > :fechaActual')
                ->setParameter('id',$id)
                ->setParameter('fechaActual',$fecha)
                ->getQuery()
                ->getArrayResult();
    }
}
