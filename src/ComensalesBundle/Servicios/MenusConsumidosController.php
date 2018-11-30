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
 * Description of MenusConsumidosController
 * @author Cristian B
 */
class MenusConsumidosController extends Controller{

    protected $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerMenusConsumidos(Request $request)
    {
        $retorno = null;
        if($fechaFin == null)
        {
            $retorno = $this->obtenerVentasDia($fechaInicio, $sede);
        }
        else
        {
            $retorno = $this->obtenerVentasPeriodo($fechaInicio, $fechaFin, $sede);
        }
        return $retorno;
    }
    
    private function obtenerMenusConsumidosDiario($fecha,$sede)
    {
        return $this->entityManager->createQueryBuilder()
                    ->select('')
                    ->from('ComensalesBundle:HistorialConsumos','hc')
                    ->innerJoin('hc.itemConsumo','item')
                    ->innerJoin('item.importe','imp')
                    ->innerJoin('hc.sedeConsumo','sed')
                    ->where('hc.fechaConsumo = :fechaElegida')
                    ->setParameter('fechaElegida',$fecha)
                    ->groupBy('')
                    ->getQuery()
                    ->getArrayResult();

    }
    
    private function obtenerMenusConsumidosPeriodo($fechaInicio,$fechaFin,$sede)
    {
        
    }
}
