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
 * Description of AdministracionController
 * @Route("/administracion",name="administracion_")
 * @Security("has_role('ROLE_ADMINISTRATIVO')")
 * @author Cristian B
 */

class AdministracionController extends Controller{
    
    /**
     * @Route("/panel",name="panel")
     */
    public function mostrarPanel(Request $request)
    {
        $sedes = $this->container->get('sedes')->obtenerSedes();
        return $this->render('Panel menus consumidos/panelMenusConsumidos.html.twig',
                array('sedes' => $sedes,
                     ));
    }
    
    /**
     * @Route("/listar",name="listar")
     */
    public function obtenerListados(Request $request)
    {
        $retorno = array();
        if($request->isXmlHttpRequest())
        {
            $sede = $request->query->get('sede');
            $fechaInicio = $request->query->get('fechaInicio');
            $fechaFin = $request->query->get('fechaFin');
            //
            if(isset($sede) && isset($fechaInicio))
            {
                $retorno[0] = $this->container->get('ventas')
                                ->obtenerVentas($fechaInicio, $fechaFin, $sede);
                $retorno[1] = $this->container->get('menus_consumidos')
                                ->obtenerMenusConsumidos($fechaInicio, $fechaFin, $sede);
            }
        }
        return new JsonResponse($retorno);
    }
    
    /**
     * @Route("/recargas",name="recargas")
     */
    public function obtenerListadoRecargas(Request $request)
    {
        $retorno = array();
        if($request->isXmlHttpRequest())
        {
            $sede = $request->query->get('sede');
            $fechaInicio = $request->query->get('fechaInicio');
            $fechaFin = $request->query->get('fechaFin');
            $retorno = $this->container->get('ventas')
                            ->obtenerListadoVentas($fechaInicio,$fechaFin,$sede);
        }
        return new JsonResponse($retorno);
    }
    
    /**
     * @Route("/consumos",name="consumos")
    */
   public function obtenerListadoConsumos(Request $request)
   {
       $retorno = array();
        if($request->isXmlHttpRequest())
        {
            $sede = $request->query->get('sede');
            $fechaInicio = $request->query->get('fechaInicio');
            $fechaFin = $request->query->get('fechaFin');
            $retorno = $this->container->get('menus_consumidos')
                            ->obtenerListadoMenusConsumidos($fechaInicio, $fechaFin, $sede);
        }
        return new JsonResponse($retorno);
   }
}
