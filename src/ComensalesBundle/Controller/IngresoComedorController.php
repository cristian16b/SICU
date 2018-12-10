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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComensalesBundle\Servicios\VentaMenusController;

/**
 * Description of IngresoComedorController
 * @Route("/comedor",name="comedor_")
 * @author Cristian B
 */
class IngresoComedorController extends Controller{
    
    /**
     * @Route("/panel",name="panel")
     */
    public function mostrarPanel(Request $request)
    {
        return $this->render('Panel ingreso comedor/panelngresoComedor.html.twig');
    }
    
    /**
     * La presente funcion es el resumen que pueden observar los empleados en el
     * punto de venta
     * @Route("/resumen",name="resumen_ingreso")
    */
    public function obtenerResumenConsumos(Request $request)
    {
        $retorno = array();
        if($request->isXmlHttpRequest())
        {
            //to-do implementar con role
            //ver como hacer
            date_default_timezone_set('America/Argentina/Cordoba');
            $fechaInicio = Date('Y-m-d');
            $hora = date("h:i:s A");
            
            $fechaFin = null;
            //to-do asociar sede al role, por ahora hardcodeado
            $sede = 'Predio';
            $retorno = $this->container->get('menus_consumidos')
                            ->obtenerMenusConsumidos($fechaInicio, $fechaFin, $sede);
            //anexo datos para los repotes
            array_push($retorno, 
                                array('sede'  => $sede,
                                       'fecha' => $fechaInicio,
                                       'hora'  => $hora
                      ));
        }
        return new JsonResponse($retorno);
   }
   
   
   /**
     * @Route("/ingreso",name="ingreso")
     */
   public function registrarMenuConsumido(Request $request)
   {
       $retorno = array();
        if($request->isXmlHttpRequest())
        {
            
        }
        return new JsonResponse($retorno);
   }
}
