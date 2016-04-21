<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductTypeController
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

class ProductTypeController extends Controller {
    
    public function addproducttypeAction (Request $request){
        $token = $request->getSession()->get('token');
         $em = $this->getDoctrine()->getManager();
        if($token){
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname()." ".$currentUser->getLastname();
             return $this->render('SettingsBundle:Default:addProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname)); 
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function saveproducttypeAction (Request $request){
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("SettingsBundle:Producttypes");
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $name = $request->get('name');
                $notes = $request->get('notes');
                
                $productType = new Producttypes();
                $productType->setName($name);
                $productType->setNotes($notes);
                $productType->setStatus("Active");
                $productType->setUsername($token->getUsername());
                $productType->setCompanyname($user->getCompanyname());
             try{  
                $em->persist($productType);
                $em->flush();
                $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                 return $this->render('SettingsBundle:Default:manageProductTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'successmsg' => "Well done ! You successfully add a Product type ",'producttypes'=> $producttypes ));
              } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
              
                return $this->render('SettingsBundle:Default:addProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Invalid Arguments. Try Again'));  
              }
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
        
    }
    
    public function manageproducttypesAction (Request $request){
        $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              $repository = $em->getRepository("LoginLoginBundle:Users");
              $user = $repository->findOneBy(array('username' => $token->getUsername()));
              $fullname = $user->getFirstname()." ".$user->getLastname();
              $repository1 = $em->getRepository("SettingsBundle:Producttypes");
              $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
            return $this->render('SettingsBundle:Default:manageProductTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttypes'=> $producttypes,'fullname'=>$fullname));
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function deleteproducttypeAction (Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Producttypes");
               $producttype = $repository1->findOneBy(array('id' => $id));
               $producttype->setStatus("Inactive");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageProductTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttypes'=> $producttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully delete the product type" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    return $this->render('SettingsBundle:Default:editProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','producttype'=> $producttype));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function editproducttypeAction(Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Producttypes");
           
            $producttype = $repository1->findOneBy(array('id' => $id));
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname()." ".$user->getLastname();
                    
            return $this->render('SettingsBundle:Default:editProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttype'=> $producttype,'fullname'=>$fullname));  
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function updateproducttypeAction(Request $request){
        $token = $request->getSession()->get('token');
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Producttypes");
            $id = $request->get('id');
            $producttype = $repository1->findOneBy(array('id' => $id));
            $producttype->setName($request->get('name'));
            $producttype->setNotes($request->get('notes'));
            try{
                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                return $this->render('SettingsBundle:Default:manageProductTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttypes'=> $producttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully update the product type" ));
       
                
            }catch(Doctrine\ORM\ORMInvalidArgumentException $e){  
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                return $this->render('SettingsBundle:Default:editProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttype'=> $producttype,'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again'));  
              
            }    
               
                    
            
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }
    
    public function activateproducttypeAction (Request $request,$id){
       $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Producttypes");
               $producttype = $repository1->findOneBy(array('id' => $id));
               $producttype->setStatus("Active");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageProductTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'producttypes'=> $producttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully activate the product type" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    return $this->render('SettingsBundle:Default:editProductType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','producttype'=> $producttype));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        } 
    }
    
    
    public function validateproducttypeAction (Request $request){
        $name = $request->get('name');
         
             
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SettingsBundle:Producttypes");
            
         $producttype = $repository->findOneBy(array('name' => $name));
         
         if($producttype){  
            return new Response("true"); 
         }else{
            return new Response("false");
             
         }
    }   
    
    public function productTypeTableDataAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $stagesArray = $this->getProductTypeData($token);
            return new Response($stagesArray);
        } else {
            return new Response(false);
        }
    }
    
    private function getProductTypeData($token) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $token->getUsername()));
        
        $repository1 = $em->getRepository("SettingsBundle:Producttypes");
        $producttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
        
        $productTypesArray = array();
        
        foreach ($producttypes as $productType) {
            //serialize obects to array
            $arrElement["id"] = $productType->getId();
            $arrElement["username"] = $productType->getUsername();
            $arrElement["productType"] = $productType->getName();
            $arrElement["notes"] = $productType->getNotes();
            $arrElement["status"] = $productType->getStatus();

            array_push($productTypesArray, $arrElement);
        }
        $response = array('productTypes' => $productTypesArray);
        return json_encode($response);
    }
}
