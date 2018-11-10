<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of GestorSolicitudController
 *
 * @author Cristian B
 */
class GestorSolicitudController extends Controller{

    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    //busco la solicitud segun el dni del presente año
    //param: dni del usuario (string)
    //retorna: solicitud del usuario
    public function obtenerSolicitudActual($dni)
    {
        $fecha = new \DateTime();
        $fecha->setDate(date("Y"), 1,1);
        $solicitud =
                $this->entityManager->createQueryBuilder()
           ->select('s')
           ->from('ComensalesBundle:Solicitud','s')
           ->innerJoin('s.persona','p')
           ->where('p.dni = :dni')
           ->andWhere('s.fechaIngreso > :fecha')
           ->setParameter('dni',$dni)
           ->setParameter('fecha',$fecha)
            ->getQuery()->getResult()
            ;
        if(empty($solicitud))
        {
            throw $this->createNotFoundException('Error n° - Solicitud no encontrado');
        }
        return $solicitud;
    }
    
}
