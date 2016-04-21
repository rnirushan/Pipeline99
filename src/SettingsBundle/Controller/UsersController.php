<?php

namespace SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;

class UsersController extends Controller {

    public function deleteUserAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");

            $admin = $repository->findOneBy(array('username' => $token->getUsername()));
            $role = $token->getRole();

            if ($admin && $role == 'Admin') {
                $adminCompanyName = $admin->getCompanyname();
                $id = $request->get("id");
                $user = $repository->findOneBy(array('id' => $id, 'companyname' => $adminCompanyName));
                if ($user) {
                    $user->setStatus("Inactive");
                    try {

                        $em->flush();
                        return new Response("true");
                    } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                        return new Response("false");
                    }
                }else{
                    return new Response("false");
                }
            } else {
                return new Response("false");
            }
        } else {
            return new Response("false");
        }
    }
    
    public function activateUserAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");

            $admin = $repository->findOneBy(array('username' => $token->getUsername()));
            $role = $token->getRole();

            if ($admin && $role == 'Admin') {
                $adminCompanyName = $admin->getCompanyname();
                $id = $request->get("id");
                $user = $repository->findOneBy(array('id' => $id, 'companyname' => $adminCompanyName));
                if ($user) {
                    $user->setStatus("Active");
                    try {

                        $em->flush();
                        return new Response("true");
                    } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                        return new Response("false");
                    }
                }else{
                    return new Response("false");
                }
            } else {
                return new Response("false");
            }
        } else {
            return new Response("false");
        }
    }

}
