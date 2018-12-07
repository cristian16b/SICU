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
}
