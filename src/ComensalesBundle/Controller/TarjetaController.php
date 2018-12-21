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
use ComensalesBundle\Controller\HistorialCRController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Description of TarjetaController
 * * @Route("/tarjetas",name="tarjetas_")
  * @author Cristian B
 */
class TarjetaController extends Controller{
   

    /**
    * @Route("/panel",name="panel")
    * @Security("has_role('ROLE_ADMINISTRATIVO')") 
    */
    public function mostrarPanel()
    {
        $organismos = $this->container->get('organismos')->obtenerOrganismos();
        return $this->render('Panel tarjetas/panelTarjetas.html.twig',
                array('organismos' => $organismos,
                     ));
    }
    
    public function crearTarjeta()
    {
      //to-do en refactorizacion del code fuente de solicitud   
    }
    
    public function obtenerTarjeta($id)
    {
        $tarjeta = $this->getDoctrine()->getEntityManager()
                        ->getRepository('ComensalesBundle:Tarjeta')->find($id);
        return $tarjeta;
    }
    
    /**
    * @Route("/cancelar",name="cancelar")     
    * @Method({"GET"}) 
    */
    public function cancelarTarjeta(Request $request)
    {
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $idTarjeta = $request->query->get('idTarjeta');
            //obtengo la entidad tarjeta
            $tarjeta = $this->obtenerTarjeta($idTarjeta);
            if(!empty($tarjeta))
            {
                //cambio 
                $estadoCancelado = $this->container->get('estadosTarjeta')->obtenerEstadoCancelado();
                //actualizo tarjeta y guardo
                $tarjeta->setEstadoTarjeta($estadoCancelado);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                $retorno = true;
            }
        }
        return new JsonResponse($retorno);
    }
    
    /**
    * @Route("/consultar/saldo",name="saldo")     
    * @Method({"GET"}) 
    */
    public function obtenerSaldo(Request $request)
    {
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $opcion = $request->query->get('opcion');
            $dni = $request->query->get('dni');
            $id = $request->query->get('id');
            if(isset($opcion))
            {
                $retorno = $this->obtenerSaldoSelector($opcion, $dni, $id);
            }
        }
        return new JsonResponse($retorno);
    }
    
    private function obtenerSaldoSelector($opcion,$dni,$id)
    {
        $saldo = null;
        if(isset($dni) && $opcion == 'dni')
        {   
            $saldo = $this->container->get('consulta_saldo')->obtenerSaldoDni($dni);
        }
        else if(isset($id) && opcion == 'id')
        {
            $tarjeta = $this->obtenerTarjeta($id);
            if($tarjeta != null)
            {
                $saldo = $tarjeta->getSaldo();
            }
        }
        return $saldo;
    }
    
    /**
    * @Route("/activar",name="activar")     
    * @Method({"GET"}) 
    */
    public function activarTarjetas(Request $request)
    {
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $lista = $request->query->get('lista');
            $tamanio = count($lista);
            for($i=0;$i<$tamanio;$i++)
            {
                $this->activarUnaTarjeta($lista[$i]);
            }
            $retorno = true;
        }
        
        return new JsonResponse($retorno);
    }
    
    private function activarUnaTarjeta($idTarjeta)
    {
        //obtengo la entidad tarjeta
        $tarjeta = $this->obtenerTarjeta($idTarjeta);
        if(!empty($tarjeta))
        {
            //cambio 
            $estadoActiva = $this->container->get('estadosTarjeta')->obtenerEstadoActiva();
            //actualizo tarjeta y guardo
            $tarjeta->setEstadoTarjeta($estadoActiva);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }
    }
    
    public function eliminarTarjeta()
    {
        //to-do para refactor de solicitud controller
        //una tarje se elimina si se elimina la solicitud a la que esta asociada
        //no existe otro caso en el que se pueda eliminar
    }
    
    /**
    * @Route("/historial",name="historial")     
    * @Method({"GET"}) 
    */
    public function obtenerHistorial(Request $request)
    {
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $id = $request->query->get('id');
            $anio = $request->query->get('anio');
            $tipoHistorial = $request->query->get('tipoHistorial');
            
            if($tipoHistorial == 'Recargas')
            {
                $retorno = $this->container->get('historialCR')
                    ->obtenerRecargas($id,$anio);
            }
            else if($tipoHistorial == 'Consumos')
            {
                $retorno = $this->container->get('historialCR')
                    ->obtenerConsumos($id,$anio);
            }
        }
        return new JsonResponse($retorno);
    }
    
    /**
    * @Route("/acreditar/saldo",name="acreditar_saldo")     
    * @Method({"GET"}) 
    */
    public function acreditarSaldo(Request $request)
    {
        $retorno = null;
        if($request->isXmlHttpRequest())
        {
            $idTarjeta = $request->query->get('id');
            $monto = $request->query->get('monto');
            
            if(isset($idTarjeta) && isset($monto) )
            {
                $tarjeta = $this->obtenerTarjeta($idTarjeta);
                
                $retorno = $this->container->get('acreditar_saldo')
                                ->acreditarSaldo($tarjeta,$monto);
                
            }
        }
        return new JsonResponse($retorno);
    }
}
