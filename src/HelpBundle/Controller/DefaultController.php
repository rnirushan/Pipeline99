<?php

namespace HelpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $token = $request->getSession()->get('token');
        if($token){
            $em = $this->getDoctrine()->getManager();
           
            $repository = $em->getRepository("LoginLoginBundle:Users");
           
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname()." ".$currentUser->getLastname();
            
            
           
            return $this->render('HelpBundle:Default:index.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname )); 
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
        
    }
}
