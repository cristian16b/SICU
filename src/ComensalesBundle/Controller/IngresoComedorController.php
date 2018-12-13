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
   
   //to-do refactorizar la clase ingresocomedor y decrementarsaldo
   //modularizar y mejorar el rendimiento de las consultas dql
   //el metodo debe ser eficiente, el comensal no debe esperar mas de 20 segundos
   //sino se pueden generar colas en el ingreso y no es la idea
   //buscar formas de medir tiempos de respuesta 
   private function obtenerTarjetaDni($dni)
   {
       $lista = $this->obtenerTarjetaEstado($dni);
       
       $retorno['error'] = '';
       $retorno['exito'] = '';
       $retorno['alerta'] = '';
       $retorno['apellidoNombre'] = '';
       $retorno['id'] = '';
       $retorno['saldo'] = '';
       //obtengo la foto en base 64    
       $retorno['fotoBase64'] = '';
       //to-do por el momento se va a hardcodeare la sede a predio
       $sede = 'Predio';
       
       if(count($lista) > 0)
       {
           //almaceno info que se muestra en el front
           $retorno['apellidoNombre'] = $lista[0]['apellido'] .' '. $lista[0]['nombre'];
           $retorno['id'] = $lista[0]['id'];
           $retorno['saldo'] = $lista[0]['saldo'];
           //obtengo la foto en base 64    
           $retorno['fotoBase64'] = $this->obtenerFotoBase64($lista[0]['dirFoto']);
           
           $retorno = $this->esActiva($retorno, $lista, $sede);
       }
       else
       {
           $retorno['error'] = 'Usuario no encontrado en el sistema, intente nuevamente o dirigase a la sede administrativa.';
       }
       return $retorno;
   }
   
   private function esActiva($retorno,$lista,$sede)
   {
       //almaceno info que sera usada posteriormente
           $estado = $lista[0]['estado'];
           //Si esta activa la tarjeta pasa
           if($estado == 'Activa') 
            {
               $retorno = $this->consumioHoy($retorno, $lista,$sede);
            }
            else
            {
                $retorno['error'] = 'La tarjeta no esta activa.';
            }
        return $retorno;
   }
   
   private function consumioHoy($retorno,$lista,$sede)
   {
       $fecha = $lista[0]['fechaUltimo'];
       //pregunto si la fecha es distinta de la actual
       if(!$this->esFechaActual($fecha))
       {
           $retorno = $this->tieneSaldo($retorno, $lista,$sede);
       }
       else
       {
           $retorno['error'] = 'Ya ha consumido en el día de la fecha.';
       }
        return $retorno;
   }
   
   private function tieneSaldo($retorno,$lista,$sede)
   {
       $tipo = $lista[0]['tipoComensal'];
       $importes = $this->obtenerImporteActual($tipo);
       $importe = $importes['precio']; 
       $saldo = $lista[0]['saldo'];

       if($saldo >= -$importe)
       {
           $retorno = $this->registroConsumo($retorno, $lista, $saldo, $importe, $sede, $tipo);
       }
       else
       {
           $retorno['error'] = 'No cuenta con saldo suficiente para ingresar.';
       }
        return $retorno;
   }
   
   private function registroConsumo($retorno,$lista,$saldo,$importe,$sede,$tipo)
   {
       //registro en el historial
       //modifico saldo y fecha en la tarjeta
       //obtengo el nuevo saldo
       $nuevoSaldo =  
            $this->container->get('decrementar_saldo')
            ->registrarConsumo($lista[0]['id'],$saldo,$importe,$sede,$tipo);

       //si la operacion es correcta el saldo debe ser menor
       //sino envio mensaje de error
       if($nuevoSaldo < $saldo)
       {
           $retorno['saldo'] = $nuevoSaldo;
           $retorno['exito'] = 'Su operación fue exitosa.';
       }
       else
       {
           $retorno['error'] = 'Su operación no fue exitosa, intente nuevamente.';
       }
       //pregunto
       if($nuevoSaldo < 0 )
       {
           $retorno['alerta'] = 'Su tarjeta se encuenta en saldo negativo, efectue una recarga en la brevedad.';
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
                           . 'tcom.nombreComensal as tipoComensal,'
                           . 'fo.nombreFisico as dirFoto')
                   ->from('ComensalesBundle:Tarjeta','tarj')
                   ->innerJoin('tarj.estadoTarjeta','est')
                   ->innerJoin('tarj.solicitud','soli')
                   ->innerJoin('soli.persona','per')
                   ->innerJoin('soli.tipo_comensal','tcom')
                   ->innerJoin('soli.foto','fo')
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
       //importante para la subconsulta, debe iniciar con ( y terminar con )
       //sino tira un error de sintaxis
       $subconsulta = '(Select max(impo.fechaActualizacion) from ComensalesBundle:Importe impo)';
       //to-do no funciona bien la consulta, revisar
       $retorno =  $this->getDoctrine()->getEntityManager()->createQueryBuilder()
                   ->select('imp.precio')
                   ->from('ComensalesBundle:Importe','imp')
                   ->where('imp.nombreImporte = :tipoIngresado')
                   ->andWhere('imp.fechaActualizacion = ' . $subconsulta )
                   ->setParameter('tipoIngresado',$tipoComensal)
                   ->setMaxResults(1)
                   ->getQuery()
                   ->getOneOrNullResult()
                ;
       return $retorno;
   }
   
    private function obtenerFotoBase64($nombreFisico)
    {   
        $base64 = null;
        
        //pregunto si tiene foto
        //to-do ver manejo de archivos con symfony
        //ver try cacht
        if($nombreFisico != null && file_exists($nombreFisico))
        {
            //obtengo la foto
            $imagenData = file_get_contents($nombreFisico);
        
            //codifico
            $base64 = base64_encode($imagenData);
        }
        
        //retorno
        return $base64;
    }
}
