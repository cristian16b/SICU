<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Servicios;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ComensalesBundle\Controller\TarjetaController;
use ComensalesBundle\Entity\HistorialConsumos;

/**
 * Description of DecrementarSaldoController
 *
 * @author Cristian B
 */
class DecrementarSaldoController extends Controller{
    
    protected $entityManager;
    
    protected $sedes;

    public function __construct($entityManager,$sedes)
    {
        $this->entityManager = $entityManager;
        $this->sedes = $sedes;
    }
    
    public function registrarConsumo($idTarjeta,$saldo,$importe,$nombreSede,$tipoComensal)
    {
        $retorno = null;
        //obtengo la tarjeta
        $tarjet = $this->obtenerTarjeta($idTarjeta);
        
        $sede = '';
        if($tarjet != null)
        {
           //update
           $tarjet->setSaldo($saldo - $importe);
           date_default_timezone_set('America/Argentina/Cordoba');
           $tarjet->setFechaUltimoConsumo(new \DateTime());
           
           $nuevoRegistro = $this->registroHistorial($tarjet,$tipoComensal,$nombreSede);

           $this->entityManager->persist($nuevoRegistro);
           $this->entityManager->persist($tarjet);
           $this->entityManager->flush();
            
            //debo obtener el saldo
            $saldo = $tarjet->getSaldo();
        
        }
        return $saldo;
    }
    
    private function registroHistorial($tarjeta,$tipoComensal,$sede)
    {
        //obtengo fecha y hora
       date_default_timezone_set('America/Argentina/Cordoba');
       $fechaRecarga = Date('Y-m-d');
       $horaRecarga = date("h:i:s A");
       //obtengo el item 
       $item = $this->obtenerItemConsumo($tipoComensal);
//       var_dump($item);
        //obtengo la sede
//       $sede = $this->sedes->obtenerSede($sede);
       $sede = $this->obtenerSede($sede);
       //registro en el historial de recargas
       $nuevoRegistro = new HistorialConsumos();
       $nuevoRegistro->setSede($sede);
       $nuevoRegistro->setTarjeta($tarjeta);
       $nuevoRegistro->setFechaHoraConsumo(new \DateTime());
       $nuevoRegistro->setItemConsumo($item);
       
       
       return $nuevoRegistro;
    }
    
    private function obtenerItemConsumo($tipo)
    {
        $nombre = 'Consumo '.$tipo;
        return $this->entityManager
                    ->getRepository('ComensalesBundle:ItemConsumo')
                    ->findOneBy
                        (array('nombreItemConsumo' => $nombre))
                    ;
    }
    
    private function obtenerTarjeta($id)
    {
        return $this->entityManager
                        ->getRepository('ComensalesBundle:Tarjeta')->find($id);
    } 
    
    private function obtenerSede($nombreSede)
    {
        return $this->entityManager
                    ->getRepository('ComensalesBundle:Sede')
                    ->findOneBy
                        (array('nombreSede' => $nombreSede))
                    ;
    }
}
