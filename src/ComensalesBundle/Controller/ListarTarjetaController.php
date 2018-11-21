<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Controller;

/**
 * Description of ListarTarjetaController
 * @author Cristian B
 */
class ListarTarjetaController {
    
    /**
    * @Route("/listar",name="listar")     
    * @Method({"GET"}) 
    */
   public function listar(Request $request)
   {
       if($request->isXmlHttpRequest())
       {
            $tipoFiltro = $request->query->get('tipoFiltro');
            $organismo  = $request->query->get('organismo');
            
            if(isset($organismo) && isset($tipoFiltro))
            {
                $this->selectorFiltros($organismo,$tipoFiltro);
            }
       }
   }
   
   private function selectorFiltros($organismo,$tipoFiltro)
   {
       switch ($tipoFiltro)
       {
           CASE "Saldo negativo":
               $this->listarSaldoNegativo($organismo);
               break;
           CASE "Saldo positivo":
               $this->listarSaldoPositivo($organismo);
               break;
           CASE "No entregadas":
               $this->listarNoEntregadas($organismo);
               break;
           CASE "Activas":
               $this->listarActivas($organismo);
               break;
           CASE "Canceladas":
               $this->listarCanceladas($organismo);
               break;
       }
   }
   
   private function listarSaldoNegativo($organismo)
   {
       
   }
   
   private function listarSaldoPositivo($organismo)
   {
       
   }
   
   private function listarNoEntregadas($organismo)
   {
       
   }
   
   private function listarActivas($organismo)
   {
       
   }
   
   private function listarCanceladas($organismo)
   {
       
   }
   
}
