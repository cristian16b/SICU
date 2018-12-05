<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of GestorTarjetaController
 *
 * @author Cristian B
 */
class ConsultaSaldoController extends Controller{
    
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function obtenerSaldoDni($dni)
    {   
        //to-do consulta tablas persona, solicitud y tarjeta
        //variable auxiliar para comparar fecha
        //genero una fecha generica (1-1-año actual)
        $fecha = new \DateTime();
        $anio = Date('Y');
        $fecha->setDate($anio, 1,1);
        
        return $this->entityManager->createQueryBuilder()
                    ->select('per.apellido,per.nombre,tarj.saldo')
                    ->from('ComensalesBundle:Solicitud','soli')
                    ->innerJoin('soli.persona','per')
                    ->innerJoin('soli.tarjeta','tarj')
                    ->where('per.dni = :dni')
                    ->andWhere('soli.fechaIngreso > :fecha')
                    ->setParameter('dni',$dni)
                    ->setParameter('fecha',$fecha)
                    ->getQuery()
                    ->getResult();
    }
}
