<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of AcreditarSaldoController
 *
 * @author Cristian B
 */
class AcreditarSaldoController extends Controller{
   
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
}
