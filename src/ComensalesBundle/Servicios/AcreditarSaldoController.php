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
    
    public function acreditarSaldo($tarjeta,$monto)
    {
        $retorno = null;
        if($tarjeta != null)
        {
            //debo obtener el saldo
            $saldo = $tarjeta->getSaldo();
            if($monto > 0 && $tarjeta != null )
            {  
               //update
               $tarjeta->setSaldo($saldo + trim($monto));
               
               $nuevoRegistro = $this->registroHistorial($tarjeta, $monto);
               
               $this->entityManager->persist($nuevoRegistro);
               $this->entityManager->flush();
               
               //retorno el saldo
               $retorno = $tarjeta->getSaldo();
            }
        }
        return $retorno;
    }
    
    private function registroHistorial($tarjeta,$monto)
    {
        //obtengo fecha y hora
       date_default_timezone_set('America/Argentina/Cordoba');
       $fechaRecarga = Date('Y-m-d');
       $horaRecarga = date("h:i:s A");

       //obtengo el item 
       //to-do por el momento solo se admite recarga por ventanilla
       //el resultado obtenido esta harcodeado
       $itemRecarga = $this->obtenerItemRecarga('Pago ventanilla');
       
       //registro en el historial de recargas
       $nuevoRegistro = new HistorialRecargas();
       $nuevoRegistro->setTarjeta($tarjeta);
       $nuevoRegistro->setFechaRecarga($fechaRecarga);
       $nuevoRegistro->setMontoRecarga($monto);
       $nuevoRegistro->setHoraRecarga($horaRecarga);
       $nuevoRegistro->setItemRecarga($itemRecarga);
       
       return $nuevoRegistro;
    }
    
    private function obtenerItemRecarga($nombre)
    {
        return $this->entityManager
                    ->getRepository('ComensalesBundle:ItemRecarga')
                    ->findOneBy
                        (array('nombreItemRecarga' => $nombre))
                    ;
    }
}
