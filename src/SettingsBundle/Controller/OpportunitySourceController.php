<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpportunitySourceController
 *
 * @author Sirojan-T
 */
namespace SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;
use SettingsBundle\Entity\Producttypes;
use SettingsBundle\Entity\Opportunitysources;

class OpportunitySourceController extends Controller {
    
    public function addopportunitysourceAction (Request $request){
        $token = $request->getSession()->get('token');
         $em = $this->getDoctrine()->getManager();
        if($token){
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname()." ".$currentUser->getLastname();
             return $this->render('SettingsBundle:Default:addOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname)); 
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function saveopportunitysourceAction (Request $request){
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $name = $request->get('name');
                $notes = $request->get('notes');
                
                $source = new Opportunitysources();
                $source->setName($name);
                $source->setNotes($notes);
                $source->setStatus("Active");
                $source->setUsername($token->getUsername());
                $source->setCompanyname($user->getCompanyname());
             try{  
                $em->persist($source);
                $em->flush();
                $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                 return $this->render('SettingsBundle:Default:manageOpportunitySources.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'successmsg' => "Well done ! You successfully add an Opportunity Source ",'sources'=> $sources ));
              } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
              
                return $this->render('SettingsBundle:Default:addOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Invalid Arguments. Try Again'));  
              }
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
        
    }
    
    public function manageopportunitysourcesAction (Request $request){
        $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              $repository = $em->getRepository("LoginLoginBundle:Users");
              $user = $repository->findOneBy(array('username' => $token->getUsername()));
              $fullname = $user->getFirstname()." ".$user->getLastname();
              $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
              $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
            return $this->render('SettingsBundle:Default:manageOpportunitySources.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'sources'=> $sources,'fullname'=>$fullname));
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function deleteopportunitysourceAction (Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
               $source = $repository1->findOneBy(array('id' => $id));
               $source->setStatus("Inactive");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageOpportunitySources.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'sources'=> $sources,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully delete one opportunity source" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    return $this->render('SettingsBundle:Default:editOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','source'=> $source));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function editopportunitysourceAction(Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
           
            $source = $repository1->findOneBy(array('id' => $id));
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname()." ".$user->getLastname();
                    
            return $this->render('SettingsBundle:Default:editOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'source'=> $source,'fullname'=>$fullname));  
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function updateopportunitysourceAction(Request $request){
        $token = $request->getSession()->get('token');
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
            $id = $request->get('id');
            $source = $repository1->findOneBy(array('id' => $id));
            $source->setName($request->get('name'));
            $source->setNotes($request->get('notes'));
            try{
                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                return $this->render('SettingsBundle:Default:manageOpportunitySources.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'sources'=> $sources,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully update one opportunity source" ));
       
                
            }catch(Doctrine\ORM\ORMInvalidArgumentException $e){  
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                return $this->render('SettingsBundle:Default:editOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'source'=> $source,'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again'));  
              
            }    
               
                    
            
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }
    
    public function activateopportunitysourceAction (Request $request,$id){
       $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
               $source = $repository1->findOneBy(array('id' => $id));
               $source->setStatus("Active");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageOpportunitySources.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'sources'=> $sources,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully activate the opportunity source" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    
                    return $this->render('SettingsBundle:Default:editOpportunitySource.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','source'=> $source));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        } 
    }
    
    public function validateopportunitysourceAction (Request $request){
        $name = $request->get('name');
         
             
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SettingsBundle:Opportunitysources");
            
         $source = $repository->findOneBy(array('name' => $name));
         
         if($source){  
            return new Response("true"); 
         }else{
            return new Response("false");
             
         }
    }  
    
    public function tableDataAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $stagesArray = $this->getOpportunitySourceData($token);
            return new Response($stagesArray);
        } else {
            return new Response(false);
        }
    }
    
    private function getOpportunitySourceData($token) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $token->getUsername()));
        
        $repository1 = $em->getRepository("SettingsBundle:Opportunitysources");
        $sources = $repository1->findBy(array('companyname' => $user->getCompanyname()));
        
        $opportunitySourceArray = array();

        foreach ($sources as $productType) {
            //serialize obects to array
            $arrElement["id"] = $productType->getId();
            $arrElement["username"] = $productType->getUsername();
            $arrElement["opportunitySource"] = $productType->getName();
            $arrElement["notes"] = $productType->getNotes();
            $arrElement["status"] = $productType->getStatus();

            array_push($opportunitySourceArray, $arrElement);
        }
        $response = array('opportunitySources' => $opportunitySourceArray);
        return json_encode($response);
    }

}
