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
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of ResumenTarjetaController
 * En esta clase, se obtienen el resumen informativo sobre 
 * + cantidad de tarjetas
 * + saldo positivo ( a favor de los comensales)
 * + saldo negativo ( deudas de los comensales)
 * @author Cristian B
 */
class ResumenTarjetaController {
    
   /**
   * @Route("tarjetas/resumen",name="tarjetas_resumen")     
   * @Method({"GET"}) 
   */
   public function obtenerResumenInformativo(Request $request)
   {
       
   }
}
