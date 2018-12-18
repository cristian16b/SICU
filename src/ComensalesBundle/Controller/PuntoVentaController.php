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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Description of PuntoVentaController
 * @Route("/ventas",name="ventas_")
 * @Security("has_role('ROLE_VENDEDOR_PREDIO')")
 * @author Cristian B
 */
class PuntoVentaController extends Controller{
    
    /**
     * @Route("/panel",name="panel")
     */
    public function mostrarPanel(Request $request)
    {
        return $this->render('Panel punto de venta/panelPuntoVenta.html.twig');
    }
    
    /**
     * La presente funcion es el resumen que pueden observar los empleados en el
     * punto de venta
     * @Route("/resumen",name="resumen_ptovta")
    */
   public function obtenerResumenVentas(Request $request)
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
            $retorno = $this->container->get('ventas')
                            ->obtenerVentas($fechaInicio, $fechaFin, $sede);
            //anexo datos para los repotes
            array_push($retorno, 
                                array('sede'  => $sede,
                                       'fecha' => $fechaInicio,
                                       'hora'  => $hora
                      ));
        }
        return new JsonResponse($retorno);
   }
}
