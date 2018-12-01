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
    
    public function obtenerMenusConsumidos($fechaInicio, $fechaFin, $sede)
    {
        $retorno = null;
        if($fechaFin == null)
        {
            $retorno = 
                    $this->obtenerTotales
                    (
                        $this->obtenerMenusConsumidosDiario($fechaInicio, $sede)
                    );
        }
        else
        {
            $retorno =
                    $this->obtenerTotales
                    (
                        $this->obtenerVentasPeriodo($fechaInicio, $fechaFin, $sede)
                    );
        }
        return $retorno;
    }
    
    /*
     * TO-DO usar el importe con al fecha mas actual con respecto a la actual
     * para poder conservar el historico de los datos
     * igualment esto queda para otra instancia y no es incluido
     * en el presente se supondra que se sobreescriben los importes
     */
    private function obtenerMenusConsumidosDiario($fecha,$sede)
    {
        return $this->entityManager->createQueryBuilder()
                    ->select('imp.nombreImporte as tipo,'
                            . 'count(hc) as cantidad,'
                            . 'imp.costo as importe')
                    ->from('ComensalesBundle:HistorialConsumos','hc')
                    ->innerJoin('hc.itemConsumo','item')
                    ->innerJoin('item.importe','imp')
                    ->innerJoin('hc.sedeConsumo','sed')
                    ->where('hc.fechaConsumo = :fechaElegida')
                    ->setParameter('fechaElegida',$fecha)
                    ->groupBy('imp.nombreImporte')
                    ->orderBy('cantidad','DESC')
                    ->getQuery()
                    ->getArrayResult();
    }
    
    private function obtenerMenusConsumidosPeriodo($fechaInicio,$fechaFin,$sede)
    {
        
    }
    
    private function obtenerTotales($retorno)
    {
        $cantidad = count($retorno);
        $acumuladoTotal = 0;
        $acumuladoCantidad = 0;
        for($i=0;$i<$cantidad;$i++)
        {
            $fila = $retorno[$i];
            $acumuladoCantidad = $acumuladoCantidad + $fila['cantidad'];
            $fila['total'] = number_format($fila['cantidad'] * $fila['importe'],2,'.','');
            $acumuladoTotal = $acumuladoTotal + $fila['total'];
            $retorno[$i] = $fila;
        }
        //agrego una ultima fila para los totales
        $filaTotales = array();
        $filaTotales['cantidad'] = $acumuladoCantidad;
        $filaTotales['total'] = number_format($acumuladoTotal, 2, '.', '');
        $retorno[$cantidad] = $filaTotales;
        
        return $retorno;
    }
}