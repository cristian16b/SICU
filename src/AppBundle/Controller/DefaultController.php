<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
//use GuzzleHttp\Psr7\Request;
//use Symfony\Component\BrowserKit\Client;
//use Symfony\Component\HttpKernel\Client;
//use Symfony\Bundle\FrameworkBundle\Client;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /////////////////////////////////////////////////
    // prueba de formulario
    /**
     * @Route("/pruebaform", name="pruebaform")
     */
    public function pruebaform(Request $request)
    {
        //ver https://uniwebsidad.com/libros/symfony-2-x/capitulo-12/creando-clases-de-formulario
        //
        //
        //$defaultData = array('message' => 'prueba');
        //$form = $this->createFormBuilder($defaultData)
        $form = $this->createFormBuilder(null)
            ->add('numero',TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();
    
          $form->handleRequest($request);
          
        if ($form->isValid() && $form->isSubmitted()) 
        {
            // data es un array con claves 'name', 'email', y 'message'
            //$data = $form->getData();
            //var_dump($data);
            echo 'numero ingresado es '. $form->get('numero')->getData();
            die;
        }
        
        return $this->render('pruebaform.html.twig',array('form' => $form->createView()));
    }
    
    
    
    
    
    /////////////////////////////////////////////////
    /**
     * @Route("/aux", name="aux")
     */
    public function ago()
    {
        return $this->render('solicitudLicencia.html.twig');
    }
    
    
    /**
     * @Route("/envio", name="envio")
     */
    public function indexAction()
    {
        $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'http://httpbin.org',
                // You can set any number of default request options.
                'timeout'  => 2.0,
                ]);
        //$response = $client->get('http://httpbin.org/get');
        //$request = new Request('PUT', 'http://httpbin.org/put');
        //$response = $client->send($request, ['timeout' => 2]);
        //$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'https://api.github.com/user', [
    'auth' => ['user', 'pass']
]);
echo $res->getStatusCode();
// "200"
echo $res->getHeader('content-type');
// 'application/json; charset=utf8'
echo $res->getBody();
        
        
        //$headers = ['X-Foo' => 'Bar'];
        //$body = 'Hello!';
        //$request = new Request('HEAD', 'http://httpbin.org/head', $headers, $body);
        //$promise = $client->requestAsync('GET', 'http://httpbin.org/get');
        
        $response = '1';
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => $response,
        ]);
    }
}
