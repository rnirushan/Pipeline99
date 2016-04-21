<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StageController
 *
 * @author Sirojan-T
 */

namespace SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;
use SettingsBundle\Entity\Stages;

class StageController extends Controller {

    public function addstageAction(Request $request) {
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        if ($token) {
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            return $this->render('SettingsBundle:Default:addStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function addstageV2Action(Request $request) {
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        if ($token) {
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            return $this->render('SettingsBundle:Stages:addStageV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function managestagesAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $repository1 = $em->getRepository("SettingsBundle:Stages");
            $stages = $repository1->findBy(array('companyname' => $user->getCompanyname()));
            return $this->render('SettingsBundle:Default:manageStages.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'stages' => $stages, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function managestagesV2Action(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();

            $stagesArray = $this->getTableData($token);

            return $this->render('SettingsBundle:Stages:manageStagesV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'fullname' => $fullname, 'stagesArray' => $stagesArray));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function tableDataAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $stagesArray = $this->getTableData($token);
            return new Response($stagesArray);
        } else {
            return new Response(false);
        }
    }

    private function getTableData($token) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $token->getUsername()));

        $repository1 = $em->getRepository("SettingsBundle:Stages");
        $stages = $repository1->findBy(array('companyname' => $user->getCompanyname()));

        $stagesArray = array();
        foreach ($stages as $stage) {
            //serialize obects to array
            $arrElement["id"] = $stage->getId();
            $arrElement["username"] = $stage->getUsername();
            $arrElement["stage"] = $stage->getName();
            $arrElement["saleChange"] = $stage->getSalechance();
            $arrElement["notes"] = $stage->getNotes();
            $arrElement["status"] = $stage->getStatus();

            array_push($stagesArray, $arrElement);
        }
        $response = array('stages' => $stagesArray);
        return json_encode($response);
    }

    public function validatestageAction(Request $request) {
        
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $token->getUsername()));
         
        $name = $request->get('name');

        if(strtolower($name) == "won" || strtolower($name) == "lost"){
            return new Response("true");
        }
        
        $repository1 = $em->getRepository("SettingsBundle:Stages");

        $stage = $repository1->findOneBy(array('name' => $name,'companyname' => $user->getCompanyname(),'status'=>"Active"));

        if ($stage) {
            return new Response("true");
        } else {
            return new Response("false");
        }
    }

    public function savestageAction(Request $request) {
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("SettingsBundle:Stages");
        if ($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')) {
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $name = $request->get('name');
            $salechance = $request->get('salechance');
            $notes = $request->get('notes');

            $stage = new Stages();
            $stage->setName($name);
            $stage->setSalechance($salechance);
            $stage->setNotes($notes);
            $stage->setStatus("Active");
            $stage->setUsername($token->getUsername());
            $stage->setCompanyname($user->getCompanyname());
            try {
                $em->persist($stage);
                $em->flush();
                
                $stagesArray = $this->getTableData($token);

                return $this->render('SettingsBundle:Stages:manageStagesV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'fullname' => $fullname, 'stagesArray' => $stagesArray, 'successmsg' => "Well done ! You successfully add the stage"));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('SettingsBundle:Default:addStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function editstageAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("SettingsBundle:Stages");

            $stage = $repository1->findOneBy(array('id' => $id));
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();


            return $this->render('SettingsBundle:Default:editStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'stage' => $stage, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }
    
    public function editstageV2Action(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("SettingsBundle:Stages");

            $stage = $repository1->findOneBy(array('id' => $id));
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();


            return $this->render('SettingsBundle:Stages:editStageV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'stage' => $stage, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function deletestageAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("SettingsBundle:Stages");
            $stage = $repository1->findOneBy(array('id' => $id));
            $stage->setStatus("Inactive");
            try {

                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $stages = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                $stagesArray = $this->getTableData($token);

                return $this->render('SettingsBundle:Stages:manageStagesV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'fullname' => $fullname, 'stagesArray' => $stagesArray, 'successmsg' => "Well done ! You successfully delete the stage"));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                return $this->render('SettingsBundle:Default:editStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Something went wrong. Try Again', 'stage' => $stage));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function activatestageAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("SettingsBundle:Stages");
            $stage = $repository1->findOneBy(array('id' => $id));
            $stage->setStatus("Active");
            try {

                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                
                $stagesArray = $this->getTableData($token);

                return $this->render('SettingsBundle:Stages:manageStagesV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'fullname' => $fullname, 'stagesArray' => $stagesArray, 'successmsg' => "Well done ! You successfully activate the stage"));
                
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                return $this->render('SettingsBundle:Default:editStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Something went wrong. Try Again', 'stage' => $stage));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function updatestageAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token && $request->getMethod() == 'POST' && ($token->getRole() == 'Admin')) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("SettingsBundle:Stages");
            $id = $request->get('id');
            $stage = $repository1->findOneBy(array('id' => $id));
            $stage->setName($request->get('name'));
            $stage->setSalechance($request->get('salechance'));
            $stage->setNotes($request->get('notes'));
            try {
                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $stages = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                $stagesArray = $this->getTableData($token);

                return $this->render('SettingsBundle:Stages:manageStagesV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'fullname' => $fullname, 'stagesArray' => $stagesArray, 'successmsg' => "Well done ! You successfully update the stage"));
            
                //return $this->render('SettingsBundle:Default:manageStages.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'stages' => $stages, 'fullname' => $fullname, 'successmsg' => "Well done ! You successfully update the stage"));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                return $this->render('SettingsBundle:Default:editStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'stage' => $stage, 'fullname' => $fullname, 'errormsg' => 'Something went wrong. Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function getsaleAction(Request $request) {
        $id = $request->get('id');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SettingsBundle:Stages");
        if ($id == 'won') {
            $salechance = '100';
        } else if ($id == 'lost') {
            $salechance = '0';
        } else {
            $stage = $repository->findOneBy(array('id' => $id));
            if ($stage) {
                $salechance = $stage->getSalechance();
            }
        }

        if (intval($salechance) >= 0) {
            return new Response($salechance);
        } else {
            return new Response("false");
        }
    }

}
