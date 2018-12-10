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
use ComensalesBundle\Servicios\VentaMenusController;

/**
 * Description of IngresoComedorController
 * @Route("/comedor",name="comedor_")
 * @author Cristian B
 */
class IngresoComedorController extends Controller{
    
    /**
     * @Route("/panel",name="panel")
     */
    public function mostrarPanel(Request $request)
    {
        return $this->render('Panel ingreso comedor/panelngresoComedor.html.twig');
    }
    
    /**
     * La presente funcion es el resumen que pueden observar los empleados en el
     * punto de venta
     * @Route("/resumen",name="resumen_ingreso")
    */
    public function obtenerResumenConsumos(Request $request)
    {
        $retorno = array();
        if($request->isXmlHttpRequest())
        {
            //to-do implementar con role
            //ver como hacer
            date_default_timezone_set('America/Argentina/Cordoba');
            $fechaInicio = Date('Y-m-d');
            $hora = date("h:i:s A");
            
            $fechaFin = null;
            //to-do asociar sede al role, por ahora hardcodeado
            $sede = 'Predio';
            $retorno = $this->container->get('menus_consumidos')
                            ->obtenerMenusConsumidos($fechaInicio, $fechaFin, $sede);
            //anexo datos para los repotes
            array_push($retorno, 
                                array('sede'  => $sede,
                                       'fecha' => $fechaInicio,
                                       'hora'  => $hora
                      ));
        }
        return new JsonResponse($retorno);
   }
   
   
   /**
     * @Route("/ingreso",name="ingreso")
     */
   public function registrarMenuConsumido(Request $request)
   {
       $retorno = array();
        if($request->isXmlHttpRequest())
        {
            $dni = $request->query->get('dni');
            $opcion = $request->query->get('opcion');
            //si existe y esta def
            if(isset($dni) && isset($opcion))
            {
                if($opcion == 'dni')
                {
                    $retorno = $this->obtenerTarjetaDni($dni);
                }
                //to-do implementar por numero de tarjeta
            }
        }
        return new JsonResponse($retorno);
   }
   
   private function obtenerTarjetaDni($dni)
   {
       $lista = $this->obtenerTarjetaEstado($dni);
       $retorno = null;
       if(count($lista) > 0)
       {
           //Si esta activa la tarjeta pasa
           if($lista[0]['estado'] == 'Activa') 
            {
               //pregunto si la fecha es distinta de la actual
               if(!$this->esFechaActual($lista[0]['fechaUltimo']))
               {
                   var_dump($this->obtenerImporteActual('Estudiante'));
                   echo 'no es fecha actual';
               }
               else
               {
                   $retorno['error'] = 'Ya ha consumido en el día de la fecha.';
               }
            }
            else
            {
                $retorno['error'] = 'La tarjeta no esta activa.';
            }
       }
       return $retorno;
   }
   
   private function obtenerTarjetaEstado($dni)
   {
        return $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('tarj.id,'
                           . 'tarj.fechaUltimoConsumo as fechaUltimo,'
                           . 'tarj.saldo,'
                           . 'est.nombreEstadoTarjeta as estado,'
                           . 'per.nombre,'
                           . 'per.apellido,'
                           . 'per.dni,'
                           . 'tcom.nombreComensal as tipoComensal')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('soli.tipo_comensal','tcom')
                   ->where('per.dni = :dniIngresado')
                   ->setParameter('dniIngresado',$dni)
                   ->getQuery()->getArrayResult()
                ;
   }
   
   //to-do agrupar en un servicio
   private function esFechaActual($fecha)
   {
      $retorno = false;
      if($fecha != null)
      {
           $fechaActual = date('Y-m-d');
           $fechaRecibida = $fecha->format('Y-m-d');
           $retorno = ($fechaActual == $fechaRecibida);
      }
      return $retorno;
   }
   
   private function obtenerImporteActual($tipoComensal)
   {
       //to-do no funciona bien la consulta, revisar
       return $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('imp.precio,'
                           . 'max(imp.fechaActualizacion) as fecha')
                   ->from('ComensalesBundle:Importe','imp')
                   ->where('imp.nombreImporte = :tipoIngresado')
                   ->setParameter('tipoIngresado',$tipoComensal)
                   ->setMaxResults(1)
                   ->getQuery()
                   ->getOneOrNullResult()
                ;
   }
}
