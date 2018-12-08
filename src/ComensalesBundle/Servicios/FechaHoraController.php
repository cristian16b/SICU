<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ComensalesBundle\Entity\HistorialRecargas;


/**
 * Description of FechaHoraController
 *
 * @author Cristian B
 */
class FechaHoraController extends Controller{

    public function __construct() {
        
    }

        public function fechaInicio($fecha)
    {
        $fechaFormateada = new \DateTime($fecha);
        return $fechaFormateada->format('Y-m-d 00:00:00');
    }
    
    public function fechaFinal($fecha)
    {
        $fechaFormateada = new \DateTime($fecha);
        return  $fechaFormateada->format('Y-m-d 23:59:59');
    }
}
