<?php

namespace ComensalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ComensalesBundle\Entity\Usuario;

class DefaultController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // Recupera el servicio de autenticación
        $authenticationUtils = $this->get('security.authentication_utils');

        // Recupera, si existe, el último error al intentar hacer login
        $error = $authenticationUtils->getLastAuthenticationError();

        // Recupera el último nombre de usuario introducido
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $u = $this->getUser();
        
        if($u != null)
        {
            $role = $u->getRole();
            if($role == 'ROLE_VENDEDOR_PREDIO')
            {
                return $this->redirect('ventas_panel');
            }
            else
            {
                echo 'ERROR';
                DIE;
            }
        }
        
        // Renderiza la plantilla, enviándole, si existen, el último error y nombre de usuario
        return $this->render('login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    
    /*
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
       // UNREACHABLE CODE
    }
    
}
