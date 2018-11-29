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
 * @Route("/menusConsumidos",name="menus_consumidos_")
 * @author Cristian B
 */
class MenusConsumidosController {

    public function __construct() {
    }
    
    /**
     * @Route("/listar",name="listar")
     */
    public function listarMenusConsumidos(Request $request)
    {
        
    }
    
    private function obtenerMenusConsumidos($fechaInicio,$fechaFin,$sede)
    {
        
    }
}
