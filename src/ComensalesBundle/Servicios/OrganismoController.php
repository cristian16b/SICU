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
 * Description of GestorOrganismoController
 *
 * @author Cristian B
 */
class OrganismoController {
    
    protected $entityManager;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerOrganismos()
    {
        $qb = $this->entityManager->createQueryBuilder()
                   ->select('o.id,o.nombreFacultad')
                   ->from('ComensalesBundle:Facultad','o')
                   ->orderBy('o.nombreFacultad','ASC')
                   ->getQuery()->getArrayResult()
        ;
        return $qb;
    }
}
