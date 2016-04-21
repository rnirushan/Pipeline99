<?php

namespace SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;

class AccountTypeController extends Controller
{
    public function addaccounttypeAction (Request $request){
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        if($token){
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname()." ".$currentUser->getLastname();
            return $this->render('SettingsBundle:Default:addAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname)); 
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function tableDataAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $stagesArray = $this->getAccountTypeData($token);
            return new Response($stagesArray);
        } else {
            return new Response(false);
        }
    }
    
    private function getAccountTypeData($token) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $token->getUsername()));
        
        $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
        $accountTypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
        
        $accountTypesArray = array();
        foreach ($accountTypes as $accountType) {
            //serialize obects to array
            $arrElement["id"] = $accountType->getId();
            $arrElement["username"] = $accountType->getUsername();
            $arrElement["accountType"] = $accountType->getName();
            $arrElement["notes"] = $accountType->getNotes();
            $arrElement["status"] = $accountType->getStatus();

            array_push($accountTypesArray, $arrElement);
        }
        $response = array('accountTypes' => $accountTypesArray);
        return json_encode($response);
    }

    public function saveaccounttypeAction (Request $request){
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $name = $request->get('name');
                $notes = $request->get('notes');
                
                $accountType = new Accounttypes();
                $accountType->setName($name);
                $accountType->setNotes($notes);
                $accountType->setStatus("Active");
                $accountType->setUsername($token->getUsername());
                $accountType->setCompanyname($user->getCompanyname());
             try{  
                $em->persist($accountType);
                $em->flush();
                $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                 return $this->render('SettingsBundle:Default:manageAccountTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'successmsg' => "Well done ! You successfully add an Account type ",'accounttypes'=> $accounttypes ));
              } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
              
                return $this->render('SettingsBundle:Default:addAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Invalid Arguments. Try Again'));  
              }
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
        
    }
    
    public function manageaccounttypesAction (Request $request){
        $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              $repository = $em->getRepository("LoginLoginBundle:Users");
              $user = $repository->findOneBy(array('username' => $token->getUsername()));
              $fullname = $user->getFirstname()." ".$user->getLastname();
              $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
              $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
            return $this->render('SettingsBundle:Default:manageAccountTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttypes'=> $accounttypes,'fullname'=>$fullname));
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function deleteaccounttypeAction (Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
               $accounttype = $repository1->findOneBy(array('id' => $id));
               $accounttype->setStatus("Inactive");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageAccountTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttypes'=> $accounttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully delete one account type" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    return $this->render('SettingsBundle:Default:editAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','accounttype'=> $accounttype));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function editaccounttypeAction(Request $request,$id){
         $token = $request->getSession()->get('token');
        if($token){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname()." ".$currentUser->getLastname();
           
            $accounttype = $repository1->findOneBy(array('id' => $id));
            
                    
            return $this->render('SettingsBundle:Default:editAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttype'=> $accounttype,'fullname'=>$fullname));  
              
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function updateaccounttypeAction(Request $request){
        $token = $request->getSession()->get('token');
        if($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')){
            $em = $this->getDoctrine()->getManager();
              
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $id = $request->get('id');
            $accounttype = $repository1->findOneBy(array('id' => $id));
            $accounttype->setName($request->get('name'));
            $accounttype->setNotes($request->get('notes'));
            try{
                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                return $this->render('SettingsBundle:Default:manageAccountTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttypes'=> $accounttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully update one account type" ));
       
                
            }catch(Doctrine\ORM\ORMInvalidArgumentException $e){  
                 $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname()." ".$user->getLastname();
                return $this->render('SettingsBundle:Default:editAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttype'=> $accounttype,'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again'));  
              
            }    
               
                    
            
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }
    
    public function activateaccounttypeAction (Request $request,$id){
       $token = $request->getSession()->get('token');
        if($token){
              $em = $this->getDoctrine()->getManager();
              
               $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
               $accounttype = $repository1->findOneBy(array('id' => $id));
               $accounttype->setStatus("Active");
               try{

                    $em->flush();
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    return $this->render('SettingsBundle:Default:manageAccountTypes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'accounttypes'=> $accounttypes,'fullname'=>$fullname,'successmsg' => "Well done ! You successfully activate the account type" ));
       
                } catch(Doctrine\ORM\ORMInvalidArgumentException $e){
                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $fullname = $user->getFirstname()." ".$user->getLastname();
                    
                    return $this->render('SettingsBundle:Default:editAccountType.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),'fullname'=>$fullname,'errormsg' => 'Something went wrong. Try Again','accounttype'=> $accounttype));  
              }
            
        }else{
            
             return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        } 
    }
    
    public function validateaccounttypeAction (Request $request){
        $name = $request->get('name');
         
             
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SettingsBundle:Accounttypes");
            
         $accounttype = $repository->findOneBy(array('name' => $name));
         
         if($accounttype){  
            return new Response("true"); 
         }else{
            return new Response("false");
             
         }
    }     
}
