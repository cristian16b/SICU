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
    
    public function obtenerConsumos($id)
    {
        return     
           $this->entityManager->createQueryBuilder()
           ->select('hc.fechaConsumo as fecha,sede.nombreSede,item.nombreItemConsumo,importe.precio')
           ->from('ComensalesBundle:HistorialConsumos','hc')
           ->innerJoin('hc.tarjeta','tarj')
           ->innerJoin('hc.itemConsumo','item')
           ->innerJoin('item.importe','importe')
           ->innerJoin('hc.sedeConsumo','sede')
           ->where('tarj.id = :id')
           ->setParameter('id',$id)
           ->getQuery()
           ->getArrayResult();
    }
    
    public function obtenerRecargas($id)
    {
        return     
           $this->entityManager->createQueryBuilder()
           ->select('hr.fechaRecarga as fecha,hr.montoRecarga,sede.nombreSede,item.nombreItemRecarga')
           ->from('ComensalesBundle:HistorialRecargas','hr')
           ->innerJoin('hr.tarjeta','tarj')
           ->innerJoin('hr.itemRecarga','item')
           ->innerJoin('hr.sedeRecarga','sede')
           ->where('tarj.id = :id')
           ->setParameter('id',$id)
           ->getQuery()
           ->getArrayResult();
    }
}
