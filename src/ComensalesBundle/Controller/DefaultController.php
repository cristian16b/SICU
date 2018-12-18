<?php

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        // Recupera el servicio de autenticación
        $authenticationUtils = $this->get('security.authentication_utils');

        // Recupera, si existe, el último error al intentar hacer login
        $error = $authenticationUtils->getLastAuthenticationError();

        // Recupera el último nombre de usuario introducido
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // Renderiza la plantilla, enviándole, si existen, el último error y nombre de usuario
        return $this->render('login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /**
     * @Route("/", name="home")
     */
    public function homeAction()
    {
        //
        $usuario = $this->getUser();
        if(null != $usuario)
        {
            $rol = $usuario->getRoles();
            if(count($rol) == 0)
            {
                //TO-DO usar throw exeption
                echo 'ERROR';
                DIE;
            }
            if($rol[0] == 'ROLE_ADMINISTRATIVO')
            {
                return $this->redirectToRoute('tarjetas_panel');
            }
            else if($rol[0] == 'ROLE_INGRESO_PREDIO')
            {
                return $this->redirectToRoute('comedor_panel');
            }
            else if($rol[0] == 'ROLE_VENDEDOR_PREDIO')
            {
                return $this->redirectToRoute('ventas_panel');
            }
            else 
            {
                return $this->redirectToRoute('login');
            }  
        }
    }


    /*
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
       // UNREACHABLE CODE
    }
}
