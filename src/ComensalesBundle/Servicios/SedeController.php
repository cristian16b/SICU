<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of GestorSedeController
 *
 * @author Cristian B
 */
class SedeController extends Controller{
    
    protected $entityManager;
    
    public function obtenerSedes()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('s.id,s.nombreSede')
           ->from('ComensalesBundle:Sede','s')
           ->orderBy('s.nombreSede','ASC')
        ;
        return $qb->getQuery()->getArrayResult();
    }
}
