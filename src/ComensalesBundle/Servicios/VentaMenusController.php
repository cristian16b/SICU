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
 * Description of VentaMenusController
 *
 * @author Cristian B
 */
class VentaMenusController extends Controller{

    protected $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerVentas($fechaInicio,$fechaFin,$sede)
    {
        $retorno = null;
        if($fechaFin == null)
        {
            $retorno = 
                        $this->obtenerTotales
                            (
                                $this->obtenerVentasDia($fechaInicio, $sede)
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
    
    private function obtenerVentasDia($fecha,$sede)
    {
        //to-do generar un servicio o controller para esto
        $fechaFormateada = new \DateTime($fecha);
        $fechaInicio = $fechaFormateada->format('Y-m-d 00:00:00');
        $fechaFinal = $fechaFormateada->format('Y-m-d 23:59:59');
        
        return     
            $this->entityManager->createQueryBuilder()
                ->select('tc.nombreComensal as tipo,'
                        . 'count(hr) as cantidad,'
                        . 'sum(hr.montoRecarga) as total'
                        )
                ->from('ComensalesBundle:HistorialRecargas','hr')
                ->innerJoin('hr.tarjeta','tarj')
                ->innerJoin('tarj.solicitud','soli')
                ->innerJoin('soli.tipo_comensal','tc')
                ->innerJoin('hr.itemRecarga','item')
                ->innerJoin('hr.sedeRecarga','sed')
                ->where('sed.nombreSede = :sedeElegida')
                ->setParameter('sedeElegida',$sede)
                ->andWhere('hr.fechaHoraRecarga BETWEEN :dateMin AND :dateMax')
                ->setParameter('dateMin',$fechaInicio)
                ->setParameter('dateMax',$fechaFinal)
                ->groupBy('tc.nombreComensal')
                ->orderBy('total','DESC')
                ->getQuery()
                ->getArrayResult();
    }
    
    private function obtenerVentasPeriodo($fechaInicio,$fechaFin,$sede)
    {
        return     
           $this->entityManager->createQueryBuilder()
                ->select('tc.nombreComensal as tipo,'
                        . 'count(hr) as cantidad,'
                        . 'sum(hr.montoRecarga) as total')
                ->from('ComensalesBundle:HistorialRecargas','hr')
                ->innerJoin('hr.tarjeta','tarj')
                ->innerJoin('hr.itemRecarga','item')
                ->innerJoin('tarj.solicitud','soli')
                ->innerJoin('soli.tipo_comensal','tc')
                ->innerJoin('hr.sedeRecarga','sed')
                ->where('hr.fechaHoraRecarga >= :fechaInicio')
                ->andWhere('hr.fechaHoraRecarga <= :fechaFin')
                ->andWhere('sed.nombreSede = :sedeElegida')
                ->setParameter('fechaInicio',$fechaInicio)
                ->setParameter('fechaFin',$fechaFin)
                ->setParameter('sedeElegida',$sede)
                ->groupBy('tc.nombreComensal')
                ->orderBy('total','DESC')
                ->getQuery()
                ->getArrayResult();
    }
    
    private function obtenerTotales($retorno)
    {
        $cantidad = count($retorno);
        $acumuladoTotal = (float)0.00;
        $acumuladoCantidad = (float)0.00;
        for($i=0;$i<$cantidad;$i++)
        {
            $fila = $retorno[$i];
            $acumuladoCantidad = $acumuladoCantidad + $fila['cantidad'];
            $acumuladoTotal = $acumuladoTotal + $fila['total'];
        }
        //agrego una ultima fila para los totales
        $filaTotales = array();
        $filaTotales['cantidad'] = $acumuladoCantidad;
        $filaTotales['total'] = number_format($acumuladoTotal, 2, '.', '');
        $retorno[$cantidad] = $filaTotales;
        
        return $retorno;
    }
    
    public function obtenerListadoVentas($fechaInicio,$fechaFin,$sede)
    {
        $retorno = null;
        if($fechaFin == null)
        {
            $retorno = 
                         $this->obtenerListadoVentasDia($fechaInicio, $sede)
                            ;
        }
        else
        {
            $retorno = 
                        $this->obtenerListadoVentasPeriodo($fechaInicio, $fechaFin, $sede)
                          ;
        }
        return $retorno;
    }
    
    private function obtenerListadoVentasDia($fechaInicio,$sede)
    {
        $fechaFormateada = new \DateTime($fechaInicio);
        $fechaInicio = $fechaFormateada->format('Y-m-d 00:00:00');
        $fechaFinal = $fechaFormateada->format('Y-m-d 23:59:59');
        return     
            $this->entityManager->createQueryBuilder()
                ->select('hr.fechaRecarga as fecha,'
                        . 'hr.horaRecarga as hora,'
                        . 'tc.nombreComensal as tipo,'
                        . 'tarj.id as tarjeta,'
                        . 'sed.nombreSede as sede')
                ->from('ComensalesBundle:HistorialRecargas','hr')
                ->innerJoin('hr.tarjeta','tarj')
                ->innerJoin('tarj.solicitud','soli')
                ->innerJoin('soli.tipo_comensal','tc')
                ->innerJoin('hr.itemRecarga','item')
                ->innerJoin('hr.sedeRecarga','sed')
                ->where('hr.fechaRecarga = :fechaElegida')
                ->andWhere('sed.nombreSede = :sedeElegida')
                ->setParameter('fechaElegida',$fechaInicio)
                ->setParameter('sedeElegida',$sede)
                ->getQuery()
                ->getArrayResult();
    }
    
    private function obtenerListadoVentasPeriodo($fechaInicio, $fechaFin, $sede)
    {
        return     
           $this->entityManager->createQueryBuilder()
                ->select('hr.fechaRecarga as fecha,'
                        . 'hr.horaRecarga as hora,'
                        . 'tc.nombreComensal as tipo,'
                        . 'tarj.id as tarjeta,'
                        . 'sed.nombreSede as sede')
                ->from('ComensalesBundle:HistorialRecargas','hr')
                ->innerJoin('hr.tarjeta','tarj')
                ->innerJoin('hr.itemRecarga','item')
                ->innerJoin('tarj.solicitud','soli')
                ->innerJoin('soli.tipo_comensal','tc')
                ->innerJoin('hr.sedeRecarga','sed')
                ->where('hr.fechaHoraRecarga >= :fechaInicio')
                ->andWhere('hr.fechaHoraRecarga <= :fechaFin')
                ->andWhere('sed.nombreSede = :sedeElegida')
                ->setParameter('fechaInicio',$fechaInicio)
                ->setParameter('fechaFin',$fechaFin)
                ->setParameter('sedeElegida',$sede)
                ->getQuery()
                ->getArrayResult();
    }
}
