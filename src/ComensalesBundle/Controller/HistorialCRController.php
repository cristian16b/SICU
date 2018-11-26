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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Description of HistorialTarjetaController
 * @author Cristian B
 */
class HistorialCRController extends Controller
{
    public function obtenerHistorialTarjeta($tarjeta)
    {
        $retorno = null;
        if(!empty($tarjeta))
        {
            //obtengo el id
            $id = $tarjeta->getId();
            //armo el historial de consumo y recarga
            //consulto
            $db = $this->getDoctrine()->getEntityManager();
            $consumos = $db->getRepository('ComensalesBundle:HistorialConsumos')->find($id);
            $recargas = $db->getRepository('ComensalesBundle:HistorialRecargas')->find($id);
            var_dump($recargas);
            var_dump($consumos);
            die;
        }
        
        return new JsonResponse($retorno);
    }
}
