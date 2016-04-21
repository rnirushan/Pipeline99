<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Login\LoginBundle\Modals\Login;

class DefaultController extends Controller {

    public function homeAction(Request $request) {
        return $this->render('LoginLoginBundle:Default:landing.html.twig');
    }

    public function signinAction(Request $request) {
        return $this->render('LoginLoginBundle:Default:signinV2.html.twig');
    }

    public function indexAction(Request $request) {
        if ($request->getMethod() == 'POST') {

            $username = $request->get('username');
            $password = sha1($request->get('password'));
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");

            $user = $repository->findOneBy(array('username' => $username, 'password' => $password));

            if ($user) {
                if ($user->getStatus() == 'Active') {
                    $fullname = $user->getFirstname() . " " . $user->getLastname();
                    $session = $this->getRequest()->getSession();
                    $session->clear();
                    $login = new Login();
                    $login->setUsername($user->getUsername());
                    $login->setPassword($password);
                    $login->setRole($user->getRole());
                    $login->setFullname($fullname);
                    $session->set('token', $login);


                    return $this->redirectToRoute('web_dashboard');
                   // return $this->render('webBundle:Default:dashboard.html.twig', array('name' => $user->getUsername(), 'role' => $user->getRole(), 'fullname' => $fullname, 'manageview' => $user->getUserView()));
                } else {
                    return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Oh snap! You are not an active user'));
                }
            } else {

                return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Oh snap! Login Failed Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig');
        }
    }

    public function forgetPasswordAction(Request $request) {
        return $this->render('LoginLoginBundle:Default:forgetPassword.html.twig');
    }

    public function signupAction(Request $request) {

        if ($request->getMethod() == 'POST') {

            $username = $request->get('username');
            $companyname = $request->get('companyname');
            $email = $request->get('email');
            $password = $request->get('password');
            $size = $request->get('size');
            $industry = $request->get('industry');
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');

            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");

            $firstuser = $repository->findOneBy(array('companyname' => $companyname));

            if ($firstuser) {

                $role = "Regular";
            } else {

                $role = "Admin";
            }


            $status = "Active";
            $user = new Users();

            $user->setCompanyname($companyname);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setCompanysize($size);
            $user->setPassword(sha1($password));
            $user->setIndustry($industry);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setRole($role);
            $user->setStatus($status);
            $user->setUserview(10);
            $user->setOpportunityview(10);
            $user->setContactview(10);
            //$em = $this->getDoctrine()->getEntityManager();
            try {
                $em->persist($user);
                $em->flush();
                return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('successmsg' => 'Registration Success,Login your account.'));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Invalid Arguments. Try Again'));
            }
        }
        return $this->render('LoginLoginBundle:Default:signinV2.html.twig');
    }

    public function signupV2Action(Request $request) {
        return $this->render('LoginLoginBundle:Default:signup.html.twig');
    }

    public function uservalidateajaxAction(Request $request) {

        $username = $request->get('username');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $user = $repository->findOneBy(array('username' => $username));

        if ($user) {
            return new Response("true");
        } else {
            return new Response("false");
        }
    }

    public function getusersAction(Request $request) {

        $sharedstring = $request->get('sharedusers');
        $originator = $request->get('user');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $originuser = $repository->findOneBy(array('username' => $originator));

        $dataArray = array("originatorname" => $originuser->getFirstname() . ' ' . $originuser->getLastname(),
            "orininatorpercentage" => $originuser->getCommissionoriginator());

        if ($sharedstring != '') {
            $sharingArray = array();
            $sharedusers = split(":", $sharedstring);
            foreach ($sharedusers as $shareduser) {
                $user = $repository->findOneBy(array('username' => $shareduser));
                $arrElement["fullname"] = $user->getFirstname() . ' ' . $user->getLastname();
                $arrElement["percentage"] = $user->getCommissionnonoriginator();
                array_push($sharingArray, $arrElement);
            }
            $dataArray["sharing"] = $sharingArray;
        }
        
        if($dataArray){
            return new Response(json_encode($dataArray));
        } else {
            return new Response("false");
        }
    }

    public function getCommissionoriginatorAction(Request $request) {

        $username = $request->get('name');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $user = $repository->findOneBy(array('username' => $username));

        if ($user) {
            return new Response($user->getCommissionoriginator());
        } else {
            return new Response("false");
        }
    }

    public function getFullnameAction(Request $request) {
        $username = $request->get('name');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $user = $repository->findOneBy(array('username' => $username));

        if ($user) {
            return new Response($user->getFirstname() . " " . $user->getLastname());
        } else {
            return new Response("false");
        }
    }

    public function logoutAction(Request $request) {

        $session = $request->getSession();
        $session->clear();
        return $this->render('LoginLoginBundle:Default:signinV2.html.twig');
    }

    public function termsofServiceAction(Request $request) {
        return $this->render('LoginLoginBundle:Default:termsofService.html.twig');
    }

    public function privacyAction(Request $request) {
        return $this->render('LoginLoginBundle:Default:privacy.html.twig');
    }

}
