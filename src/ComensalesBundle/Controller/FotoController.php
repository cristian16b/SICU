<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ComensalesBundle\Controller;

use ComensalesBundle\Entity\Foto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
/**
 * Description of FotoController
 *
 * @author Cristian B
 */
class FotoController  extends Controller
{
    
    public function seteoFoto($file,$canvas,$dni)
    {
        $foto = new Foto();
        
        //var_dump($file);
        //var_dump($canvas);
        $fileSystem = new Filesystem();
        $path = 'img/fotos/' . $dni;
        if($fileSystem->exists($path))
        {
            $fileSystem->remove($path);
        }
        
        if(strlen($canvas) > 31)
            {  
                $foto =  $this->guardarCanvas($canvas,$foto,$dni);
            }
        else
        {
           //por algun motivo, falla si uso empty o is null, cadena vacia viene de 31 chars
            if(is_null($file) != true)
            {
               $foto = $this->guardarFoto($file, $foto, $dni);
            }
            else
            {
                //retorno una foto vacia
                $foto->setNombre($dni);
                $foto->setNombreFisico($path);
                $foto->setPeso(0);
            }
        }
        return $foto;
    }
    
    private function guardarFoto($file,$foto,$dni)
    {
        
        $fileName = $dni.'.'.$file->guessExtension();
        $file->move
                (
                $this->getParameter('fotos_directorio'),
                $fileName
                )
                ;
            //seteo los atributos 
        $foto->setNombre($fileName);
        $foto->setPeso($file->getClientSize());
        //OJO DINAMIZAR PARA QUE NO SEA FIJO USAR CONSTANTE GLOBAL O SIMIL
        $path = "\\xampp\\htdocs\\SICU\\web\\img\\fotos\\" . $fileName ;
        $foto->setNombreFisico($path);
        
        return $foto;
    }

    
    private function guardarCanvas($canvas,$foto,$dni)
    {
        //https://es.ourcodeworld.com/articulos/leer/4/como-guardar-una-imagen-en-formato-base64-generada-con-javascript-en-el-servidor-con-php
        
        $basetophp = explode(',',$canvas);
        $data = base64_decode($basetophp[1]);
        $fileName = $dni .".png";
        $path = "\\xampp\\htdocs\\SICU\\web\\img\\fotos\\" . $fileName ;
        file_put_contents($path, $data);
        //
        $foto->setNombre($fileName);
        $foto->setPeso(filesize($path));
        $foto->setNombreFisico($path);
        
        return $foto;
    }
}
