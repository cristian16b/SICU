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

/**
 * Description of AdministracionController
 * @Route("/administracion",name="administracion_")
 * @author Cristian B
 */

class AdministracionController extends Controller{
    
    private $recargas;
    private $consumos;

    public function __construct() 
    {
        $this->recargas = new VentaMenusController();
        $this->consumos = new MenusConsumidosController();
    }

        /**
     * @Route("/panel",name="panel")
     */
    public function mostrarPanel()
    {
        $sedes = $this->container->get('sedes')->obtenerSedes();
        return $this->render('Panel menus consumidos/panelMenusConsumidos.html.twig',
                array('sedes' => $sedes,
                     ));
    }
}
