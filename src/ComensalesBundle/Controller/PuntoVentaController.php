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
 * Description of PuntoVentaController
 * @Route("/ventas",name="ventas_")
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
    
    
    
}
