<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of EstadoTarjetaController
 *
 * @author Cristian B
 */
class EstadoTarjetaController extends Controller 
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerEstados()
    {
        //to-do implementar func que retorna un json con los nombres de todos los estado
        //opcional que retorne las entidades
    }
    
    public function obtenerEstadoActiva()
    {
        $nombreEstado = 'Activa';
        $estadoActiva = $this->entityManager
                         ->getRepository('ComensalesBundle:EstadoTarjeta')
                         ->findBynombreEstadoTarjeta($nombreEstado);
        if(empty($estadoActiva))
        {
            $estadoActiva = null;
        }
        return $estadoActiva[0];
    }

    public function obtenerEstadoCancelado()
    {
        $nombreEstado = 'Cancelada';
        $estadoCancelado = $this->entityManager
                         ->getRepository('ComensalesBundle:EstadoTarjeta')
                         ->findBynombreEstadoTarjeta($nombreEstado);
        if(empty($estadoCancelado))
        {
            $estadoCancelado = null;
        }
        return $estadoCancelado[0];
    }
}
