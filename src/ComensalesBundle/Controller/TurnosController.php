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
        $sedes = $this->container->get('sedes')->obtenerSedes();
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
    
    /**
    * @Route("/modificar/cupo",name="modificar_cupo")     
    * @Method({"GET"}) 
    */
    public function modificarCupo(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $listaHorarios = $request->query->get('listaHorarios');
            $bandera = $request->query->get('bandera');
            $cantidad = $request->query->get('cantidad');
            $servicio = $this->get('gestor_turnos');
            if($bandera == 'Decrementar')
            {
                $cantidad = $cantidad * -1; 
            }
            return $servicio->modificarCupo($sede,$fecha,$listaHorarios,$cantidad);
        }
    }

    /**
    * @Route("/eliminar/turno",name="eliminar_turno")     
    * @Method({"GET"}) 
    */
    public function eliminarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $listaHorarios = $request->query->get('listaHorarios');
            
            $servicio = $this->get('gestor_turnos');
            return $servicio->eliminarHorarios($sede, $fecha, $listaHorarios);
        }
    }
    
    /**
    * @Route("/eliminar/solicitante",name="eliminar_solicitante_turno")     
    * @Method({"GET"}) 
    */
    public function eliminarSolicitantesTurnos(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $listaSolicitantes = $request->query->get('listaSolicitantes');
            $servicio = $this->get('gestor_turnos');
            return $servicio->eliminarSolicitanteHorarios($listaSolicitantes);
        }
    }
    
    /**
    * @Route("/listar-horarios",name="listar_horarios")     
    * @Method({"GET"}) 
    */
    public function obtenerHorarios(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->obtenerHorarios($sede,$fecha);
        }
    }

    /**
    * @Route("/cambiar-turno",name="cambiar_turno")     
    * @Method({"GET"}) 
    */
    public function cambiarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $sede = $request->query->get('sede');
            $fecha = $request->query->get('fecha');
            $horario = $request->query->get('horario');
            $dni = $request->query->get('dni');
            
            $servicio = $this->container->get('gestor_turnos');
            return $servicio->cambiarTurno($sede,$fecha,$horario,$dni);
        }
    }
    
    /**
    * @Route("/buscar-turno",name="buscar_turno")     
    * @Method({"GET"}) 
    */
    public function buscarTurno(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            //leo el request
            $dni = $request->query->get('dato');
            $opcion = $request->query->get('opcion');
            
            $retorno = NULL;
            if($opcion === 'Dni')
            {
                $servicio = $this->container->get('gestor_turnos');
                $retorno =  $servicio->buscarTurnoDni($dni);
            }
            return $retorno;
        }
    }
}
