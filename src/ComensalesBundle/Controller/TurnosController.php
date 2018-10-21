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
use Symfony\Component\HttpFoundation\JsonResponse;
use ComensalesBundle\Controller\GestorTurnoController;


/**
 * Description of TurnosController
 * @Route("/turnos",name="turnos_")
 * @author Cristian B
 */
class TurnosController extends Controller{
      
    /**
    * @Route("/panel",name="panel")     * 
    */
    public function mostrarPanel()
    {
        $sedes = $this->container->get('gestor_turnos')->obtenerSedes();
        return $this->render('Panel turnos/panelTurnos.html.twig',
                array('sedes' => $sedes,
                     ));        
    }
    
    /**
    * @Route("/listar",name="listar")     
    * @Method({"GET"}) 
    */
    public function listarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->listar($sede, $fecha);
        }
    }
    
    /**
    * @Route("/listar/solicitantes",name="listar_solicitantes")     
    * @Method({"GET"}) 
    */
    public function listarSolicitantesTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $horario = $request->query->get('horario');
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->listarSolicitantesTurno($sede,$fecha,$horario);
        }
    }
    
    /**
    * @Route("/crear/turnos",name="crear_turnos")     
    * @Method({"GET"}) 
    */
    public function crearTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $horaInicio = $request->query->get('horaInicio');
            $horaFin = $request->query->get('horaFin');
            $cupo = $request->query->get('cupo');
             
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->crearTurno($sede,$fecha,$horaInicio,$horaFin,$cupo);
        }
    }


    ///////////////////////////
    /**
    * @Route("/obtener-turno",name="obtener-turno")     
    * @Method({"GET"}) 
    */
    public function obtenerHorarios(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede-obtener');
            $fecha = $request->query->get('fecha-obtener');
            
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->listar();
        }
    }
    
    /**
    * @Route("/agregar-turno",name="actualizar_s")     
    * @Method({"GET"}) 
    */
    public function agregarCupo(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede-agregar');
            $fecha = $request->query->get('fecha-agregar');
            $horario = $request->query->get('horario-agregar');
            $cantidad = $request->query->get('cantidad-nuevos-cupos');
            
            $servicio = $this->get('gestor_turnos');
            return $servicio->modificarCupo($sede,$fecha,$horario,$cantidad);
        }
    }
    
    /**
    * @Route("/decrementar-turno",name="actualizar_s")     
    * @Method({"GET"}) 
    */
    public function decrementarCupo(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede-quitar');
            $fecha = $request->query->get('fecha-quitar');
            $horario = $request->query->get('horario-quitar');
            $cantidad = $request->query->get('cantidad-quitar') * (-1);
            
            $servicio = $this->get('gestor_turnos');
            return $servicio->modificarCupo($sede,$fecha,$horario,$cantidad);
        }
    }
    
    /**
    * @Route("/eliminar-turno",name="eliminar-turno")     
    * @Method({"GET"}) 
    */
    public function eliminarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede-eliminar');
            $fecha = $request->query->get('fecha-eliminar');
            $horario = $request->query->get('horario-eliminar');
            
            $servicio = $this->get('gestor_turnos');
            return $servicio->eliminarTurno($sede, $fecha, $horario);
        }
    }
    
    //cancelar y reasignar el turno
    /**
    * @Route("/cambiar-turno",name="cambiar-turno")     
    * @Method({"GET"}) 
    */
    public function cambiarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede-cambiar');
            $fecha = $request->query->get('fecha-cambiar');
            $horario = $request->query->get('horario-cambiar');
            $dni = $request->query->get('dni');
            $nroSolicitud = $request->query->get('nro-solicitud');
            
            $servicio = $this->get('gestor_turnos');
            return $servicio->cambiarTurno($sede, $fecha, $horario, $dni, $nroSolicitud);
        }
    }
    
    
}
