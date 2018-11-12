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
 * Description of TarjetaController
 * * @Route("/panel",name="panel")
 * @author Cristian B
 */
class TarjetaController extends Controller{
    
    /**
    * @Route("/panel",name="panel")     * 
    */
    public function mostrarPanel()
    {
        return $this->render('Panel tarjetas/panelTarjetas.html.twig');
    }
}
