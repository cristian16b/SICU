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
 * Description of AdministracionController
 * @Route("/administracion",name="administracion_")
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
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $sede = $request->query->get('sede');
            $fechaInicio = $request->query->get('fechaInicio');
            $fechaFin = $request->query->get('fechaFin');
            //
            if(isset($sede) && isset($fechaInicio))
            {
                var_dump('pasa');
                $retorno = $this->container->get('ventas')
                                ->obtenerVentas($fechaInicio, $fechaFin, $sede);
            }
        }
        return new JsonResponse($retorno);
    }
}
