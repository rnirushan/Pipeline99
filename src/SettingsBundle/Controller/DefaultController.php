<?php

namespace SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('SettingsBundle:Default:index.html.twig', array('name' => $name));
    }

    public function pipelinesetupAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }
            $stagesArray = $this->getStageTableData($token);
            $accountTypesArray = $this->getAccountTypeData($token);
            $productTypesArray = $this->getProductTypeData($token);
            $opportunitySourceArray = $this->getOpportunitySourceData($token);
            $userArray = $this->getUserData($token);

            return $this->render('SettingsBundle:Default:pipelinesetup.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'fullname' => $fullname, 'stagesArray' => $stagesArray, 'accountTypeArray' => $accountTypesArray,
                        'productTypeArray' => $productTypesArray, 'opportunitySourceArray' => $opportunitySourceArray, 'userArray' => $userArray,
                        'manageview' => $user->getUserview(), 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    private function getStageTableData($token) {
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

    private function getUserData($token) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");

        $admin = $repository->findOneBy(array('username' => $token->getUsername()));
        $role = $token->getRole();
        if ($role == 'Admin' || $role == 'Regular') {

            $admin_companyName = $admin->getCompanyname();

            $users = $repository->findBy(array('companyname' => $admin_companyName));
            foreach ($users as $user) {
                $wonAmount = 0;
                $lossAmount = 0;
                $originOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active'));
                $ownedOpportunityCount = count($originOpportunities);
                $wonOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active', 'stage' => '6'));
                $wonOpportunityCount = count($wonOpportunities);
                foreach ($wonOpportunities as $wonOpportunity) {
                    $wonAmount += intval(str_replace(",", "", $wonOpportunity->getProjectedrevenue()));
                }
                $lossOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active', 'stage' => '7'));
                $lossOpportunityCount = count($lossOpportunities);
                foreach ($lossOpportunities as $lossOpportunity) {
                    $lossAmount += intval(str_replace(",", "", $lossOpportunity->getProjectedrevenue()));
                }
                $allOpprtunities = $repository1->findBy(array('status' => 'Active'));
                $totalCount = 0;
                $wonCount = 0;

                $lossCount = 0;
                foreach ($allOpprtunities as $opportunity) {
                    $sharingString = $opportunity->getSharing();
                    if ($sharingString != '') {
                        $splitedArray = split(':', $sharingString);
                        foreach ($splitedArray as $sharedname) {
                            if (strtolower($user->getUsername()) == strtolower($sharedname)) {
                                $totalCount++;
                                if ($opportunity->getStage() == '6') {
                                    $wonCount++;
                                    $wonAmount += intval(str_replace(",", "", $opportunity->getRevenue()));
                                }
                                if ($opportunity->getStage() == '7') {
                                    $lossCount++;
                                    $lossAmount += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                                }
                            }
                        }
                    }
                }
                $totalWonOpportunityCount = intval($wonOpportunityCount) + intval($wonCount);
                $totalLossOpportunityCount = intval($lossOpportunityCount) + intval($lossCount);
                $totalOpportunityCount = intval($ownedOpportunityCount) + intval($totalCount);
                $user->setWondealcount(number_format($wonAmount));
                $user->setLossdealcount(number_format($lossAmount));
                $user->setOpendealcount($totalOpportunityCount - $totalWonOpportunityCount - $totalLossOpportunityCount);
                $user = $this->revenueAndForecastCalculation($em, $user);
            }
        } else if ($role == 'Master Admin') {
            $users = $repository->findAll();
            foreach ($users as $user) {
                $user = $this->revenueAndForecastCalculation($em, $user);
            }
        }




        if ($admin) {
            $fullname = $admin->getFirstname() . " " . $admin->getLastname();
            //echo json_encode($response);exit;

            $userArray = array();
            //serialize user obects to array
            foreach ($users as $tempUser) {

                $arrElement["firstname"] = $tempUser->getFirstname();
                $arrElement["lastname"] = $tempUser->getLastname();
                $arrElement["username"] = $tempUser->getUsername();
                $arrElement["id"] = $tempUser->getId();
                $arrElement["projectedRevenue"] = $tempUser->getProjectedrevenue();
                $arrElement["individualForecast"] = $tempUser->getIndividualforecast();
                $arrElement["status"] = $tempUser->getStatus();
                $arrElement["role"] = $tempUser->getRole();

                $arrElement["title"] = $tempUser->getJobtitle();
                $arrElement["company"] = $tempUser->getCompanyname();
                $arrElement["email"] = $tempUser->getEmail();
                $arrElement["telephone"] = $tempUser->getTelephoneoffice();
                $arrElement["cellphone"] = $tempUser->getTelephonemobile();
                $arrElement["originator"] = $tempUser->getCommissionoriginator();
                $arrElement["nonOriginator"] = $tempUser->getCommissionnonoriginator();
                $arrElement["drawAgainstCommission"] = $tempUser->getAnnualdraw();
                $arrElement["earningGoals"] = $tempUser->getEarninggoal();
                $arrElement["openDeals"] = $tempUser->getOpendealcount();
                $arrElement["lossDeals"] = $tempUser->getLossdealcount();
                $arrElement["wonDeals"] = $tempUser->getWondealcount();
                $arrElement["dob"] = $tempUser->getDob();


                array_push($userArray, $arrElement);
            }
            $response = array('users' => $userArray);
            return json_encode($response);
        }
    }
    
    public function revenueAndForecastCalculation($em, $user) {

        $repository = $em->getRepository("LoginLoginBundle:Users");
        $username = $user->getUsername();
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
        $userforecast = 0;
        $userrevenue = 0;
        $opportunityCount = 0;



        if ($opportunities) {

            foreach ($opportunities as $opportunity) {

                $shareduserstring = $opportunity->getSharing();
                if ($shareduserstring != '') {
                    $sharedusers = explode(":", $shareduserstring);
                    $originator = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                    $sharedPercentage = $originator->getCommissionoriginator();
                    foreach ($sharedusers as $shareduser) {

                        $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                        if($sharingUser){
                            $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                        }
                    }

                    if ((strtolower($username) == strtolower($opportunity->getUsername())) && $sharedPercentage >= 100) {

                        $userforecast += intval(str_replace(",", "", $opportunity->getForecast()) * $originator->getCommissionoriginator()) / 100;
                        $userrevenue += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                        $opportunityCount++;
                    } else if ((strtolower($username) == strtolower($opportunity->getUsername())) && $sharedPercentage < 100) {

                        $userforecast += intval(str_replace(",", "", $opportunity->getForecast()) * ($originator->getCommissionoriginator() + (100 - $sharedPercentage))) / 100;
                        $userrevenue += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                        $opportunityCount++;
                    } else if (in_array(strtolower($username), array_map('strtolower', $sharedusers)) && $sharedPercentage > 100) {

                        $userforecast += intval(str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - (($sharedPercentage - 100 ) / count($sharedusers)))) / 100;
                        $userrevenue += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                        $opportunityCount++;
                    } else if (in_array(strtolower($username), array_map('strtolower', $sharedusers)) && $sharedPercentage <= 100) {

                        $userforecast += intval(str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                        $userrevenue += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                        $opportunityCount++;
                    }
                } else {

                    if (strtolower($opportunity->getUsername()) == strtolower($username)) {

                        $userforecast += intval(str_replace(",", "", $opportunity->getForecast()));
                        $userrevenue += intval(str_replace(",", "", $opportunity->getProjectedrevenue()));
                        $opportunityCount++;
                    }
                }
            }
        }

        $userforecast = number_format($userforecast, 0);
        $userrevenue = number_format($userrevenue, 0);

        $user->setIndividualforecast($userforecast);
        $user->setProjectedrevenue($userrevenue);
        $user->setNoofopportunity($opportunityCount);

        return $user;
    }
}