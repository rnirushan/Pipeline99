<?php

namespace OpportunityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Login\LoginBundle\Modals\Login;
use OpportunityBundle\Entity\Opportunities;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('OpportunityBundle:Default:index.html.twig', array('name' => $name));
    }

    public function addopportunityAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));


            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));


            return $this->render('OpportunityBundle:Default:addOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'accounttypes' => $accounttypes, 'stages' => $stages, 'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources, 'users' => $users, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function addcontactopportunityAction(Request $request, $name, $orgname, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));


            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

            $opportunityname = $name;
            $organizationname = $orgname;

            return $this->render('OpportunityBundle:Default:addOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'accounttypes' => $accounttypes, 'stages' => $stages, 'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources, 'users' => $users, 'fullname' => $fullname, 'personname' => $opportunityname, 'organizationname' => $organizationname, 'contactid' => $id));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function addcontactopportunityV2Action(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");
            $repository5 = $em->getRepository("ContactsContactsBundle:Contacts");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));


            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

            $selectedContact = $repository5->findOneBy(array('id' => $id));
            $opportunityname = $selectedContact->getName();
            $organizationname = $selectedContact->getCompany();

            return $this->render('OpportunityBundle:Default:addOpportunityV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'accounttypes' => $accounttypes, 'stages' => $stages,
                        'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources,
                        'users' => $users, 'fullname' => $fullname, 'personname' => $opportunityname,
                        'organizationname' => $organizationname, 'contactid' => $id,
                        'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function saveopportunityAction(Request $request) {
        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        if ($token && $request->getMethod() == 'POST') {
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $opportunitie = new Opportunities();
            $opportunitie->setUsername($token->getUsername());
            $opportunitie->setOwnedcompany($user->getCompanyname());
            $opportunitie->setStatus("Active");
            $opportunitie->setPersonname($request->get('personname'));
            $opportunitie->setOrganizationname($request->get('organizationname'));
            $opportunitie->setProjectedrevenue($request->get('projectedrevenue'));
            $opportunitie->setAccounttype($request->get('accounttype'));
            $opportunitie->setStage($request->get('stage'));
            $opportunitie->setProducttype($request->get('producttype'));
            $opportunitie->setOpportunitysource($request->get('opportunitysource'));
            $opportunitie->setNotes($request->get('notes'));
            $opportunitie->setVisibility($request->get('visibility'));
            $opportunitie->setForecast($request->get('forecast'));
            $opportunitie->setCloseddate($request->get('closeddate'));
            $opportunitie->setRevenue($request->get('revenue'));
            $opportunitie->setUserrevenue($request->get('userrevenue'));
            $opportunitie->setReason($request->get('reason'));
            $opportunitie->setContactid($request->get('contactid'));
            $opportunitie->setWonnotes($request->get('modalnoteshidden'));
            $opportunitie->setTags($request->get('tags'));
            $opportunitie->setPartnername($request->get('partnername'));
            $opportunitie->setPartnerpercentage($request->get('partnerpercentage'));
            $sharingusers = '';
            $colonFlag = true;
            if ($request->get('sharingusers')) {
                foreach ($request->get('sharingusers') as $sharinguser) {
                    if (!$colonFlag) {
                        $sharingusers .= ':';
                    }
                    $sharingusers .= $sharinguser;
                    $colonFlag = false;
                }
            }
            $opportunitie->setSharing($sharingusers);
            try {
                $em->persist($opportunitie);
                $em->flush();
                $repository2 = $em->getRepository("SettingsBundle:Stages");

                $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository4 = $em->getRepository("SettingsBundle:Producttypes");
                $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $image = $user->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }

                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
                foreach ($opportunities as $opportunity) {
                    $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                    $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
                    $opportunity->setOwnerfullname($ownedUserfullname);
                    $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
                    if ($stage) {
                        $opportunity->setChanceofsale($stage->getSalechance());
                        $opportunity->setStage($stage->getName());
                    }
                    if ($opportunity->getStage() == 'won') {
                        $opportunity->setChanceofsale('100');
                    }
                    if ($opportunity->getStage() == 'lost') {
                        $opportunity->setChanceofsale('0');
                    }
                    $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
                    if ($accounttype) {
                        $opportunity->setAccounttype($accounttype->getName());
                    }

                    $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
                    if ($producttype) {
                        $opportunity->setProducttype($producttype->getName());
                    }

                    $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
                    if ($opportunitysource) {
                        $opportunity->setOpportunitysource($opportunitysource->getName());
                    }


                    $sharinguserstring = $opportunity->getSharing();
                    $sharedusers = explode(":", $sharinguserstring);
                    $sharedPercentage = 0;
                    $individualforecast = -1;

                    if ($sharinguserstring != '') {

                        foreach ($sharedusers as $shareduser) {

                            $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                            if ($sharingUser) {
                                $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                            }
                        }

                        if ($opportunity->getUsername() == $token->getUsername()) {
                            if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                                //$individualforecast = 1200;
                                $decimalCount = explode(".", $individualforecast);
                                //echo count($decimalCount);
                                //exit;
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        } else if (in_array($token->getUsername(), $sharedusers)) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        }
                    }
                    $opportunity->setCurrentuserforecast($individualforecast);
                }
                $opportunitiesArray = $this->getOpportunityArray($token, '-1', '');

                return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', array('name' => $token->getUsername(), 'opportunitiesArray' => $opportunitiesArray,
                            'role' => $token->getRole(), 'fullname' => $fullname,
                            'successmsg' => "Well done ! You successfully add an Opportunity ",
                            'opportunities' => $opportunities,
                            'manageview' => $user->getOpportunityview(), 'image' => $image));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('OpportunityBundle:Default:addOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function editopportunityAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");
            $repository5 = $em->getRepository("OpportunityBundle:Opportunities");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));

            $opportunity = $repository5->findOneBy(array('id' => $id));

            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();

            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

            return $this->render('OpportunityBundle:Default:editOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'accounttypes' => $accounttypes, 'stages' => $stages, 'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources, 'opportunity' => $opportunity, 'users' => $users, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function editopportunityV2Action(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");
            $repository5 = $em->getRepository("OpportunityBundle:Opportunities");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));

            $opportunity = $repository5->findOneBy(array('id' => $id));

            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();

            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

            return $this->render('OpportunityBundle:Default:editOpportunityV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'accounttypes' => $accounttypes, 'stages' => $stages,
                        'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources,
                        'opportunity' => $opportunity, 'users' => $users, 'fullname' => $fullname,
                        'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function limitededitopportunityAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Producttypes");
            $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");
            $repository5 = $em->getRepository("OpportunityBundle:Opportunities");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
            $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'), array('name' => 'ASC'));
            $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));

            $opportunity = $repository5->findOneBy(array('id' => $id));

            $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();
            $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

            return $this->render('OpportunityBundle:Default:editOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'accounttypes' => $accounttypes, 'stages' => $stages, 'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources, 'opportunity' => $opportunity, 'users' => $users, 'fullname' => $fullname, 'flag' => 'true', 'successmsg' => "You can edit the Notes only! "));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function activateopportunityAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
            $opportunitie = $repository1->findOneBy(array('id' => $id));
            $opportunitie->setStatus("Active");
            try {

                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository4 = $em->getRepository("SettingsBundle:Producttypes");
                $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
                foreach ($opportunities as $opportunity) {
                    $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                    $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
                    $opportunity->setOwnerfullname($ownedUserfullname);
                    $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
                    if ($stage) {
                        $opportunity->setChanceofsale($stage->getSalechance());
                        $opportunity->setStage($stage->getName());
                    }
                    if ($opportunity->getStage() == 'won') {
                        $opportunity->setChanceofsale('100');
                    }
                    if ($opportunity->getStage() == 'lost') {
                        $opportunity->setChanceofsale('0');
                    }
                    $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
                    if ($accounttype) {
                        $opportunity->setAccounttype($accounttype->getName());
                    }

                    $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
                    if ($producttype) {
                        $opportunity->setProducttype($producttype->getName());
                    }

                    $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
                    if ($opportunitysource) {
                        $opportunity->setOpportunitysource($opportunitysource->getName());
                    }


                    $sharinguserstring = $opportunity->getSharing();
                    $sharedusers = explode(":", $sharinguserstring);
                    $sharedPercentage = 0;
                    $individualforecast = -1;

                    if ($sharinguserstring != '') {

                        foreach ($sharedusers as $shareduser) {

                            $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                            if ($sharingUser) {
                                $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                            }
                        }

                        if ($opportunity->getUsername() == $token->getUsername()) {
                            if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                                //$individualforecast = 1200;
                                $decimalCount = explode(".", $individualforecast);
                                //echo count($decimalCount);
                                //exit;
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        } else if (in_array($token->getUsername(), $sharedusers)) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        }
                    }
                    $opportunity->setCurrentuserforecast($individualforecast);
                }
                return $this->render('OpportunityBundle:Default:manageOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'opportunities' => $opportunities, 'fullname' => $fullname, 'successmsg' => "Well done ! You successfully activate the opportunity", 'manageview' => $user->getOpportunityview()));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository5 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Producttypes");
                $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");


                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $accounttypes = $repository5->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));

                $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
                $currentCompany = $currentUser->getCompanyname();
                $users = $repository->findBy(array('companyname' => $currentCompany, 'status' => 'Active'));

                return $this->render('OpportunityBundle:Default:editOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Something went wrong. Try Again', 'opportunity' => $opportunity, 'accounttypes' => $accounttypes, 'stages' => $stages, 'producttypes' => $producttypes, 'opportunitysources' => $opportunitysources, 'users' => $users));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function manageopportunityAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
            $repository2 = $em->getRepository("SettingsBundle:Stages");
            $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
            $repository4 = $em->getRepository("SettingsBundle:Producttypes");
            $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));

            foreach ($opportunities as $opportunity) {
                $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
                $opportunity->setOwnerfullname($ownedUserfullname);
                $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
                if ($stage) {
                    $opportunity->setChanceofsale($stage->getSalechance());
                    $opportunity->setStage($stage->getName());
                }
                if ($opportunity->getStage() == 'won') {
                    $opportunity->setChanceofsale('100');
                }
                if ($opportunity->getStage() == 'lost') {
                    $opportunity->setChanceofsale('0');
                }
                $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
                if ($accounttype) {
                    $opportunity->setAccounttype($accounttype->getName());
                }

                $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
                if ($producttype) {
                    $opportunity->setProducttype($producttype->getName());
                }

                $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
                if ($opportunitysource) {
                    $opportunity->setOpportunitysource($opportunitysource->getName());
                }


                $sharinguserstring = $opportunity->getSharing();
                $sharedusers = explode(":", $sharinguserstring);
                $sharedPercentage = 0;
                $individualforecast = -1;

                if ($sharinguserstring != '') {

                    foreach ($sharedusers as $shareduser) {

                        $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                        if ($sharingUser) {
                            $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                        }
                    }

                    if ($opportunity->getUsername() == $token->getUsername()) {
                        if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                            $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                            //$individualforecast = 1200;
                            $decimalCount = explode(".", $individualforecast);
                            //echo count($decimalCount);
                            //exit;
                            if (count($decimalCount) > 1) {
                                $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                            } else {
                                $individualforecast = number_format($individualforecast, 0);
                            }
                        } else {
                            $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                            $decimalCount = explode(".", $individualforecast);
                            if (count($decimalCount) > 1) {
                                $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                            } else {
                                $individualforecast = number_format($individualforecast, 0);
                            }
                        }
                    } else if (in_array($token->getUsername(), $sharedusers)) {
                        if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                            $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                            $decimalCount = explode(".", $individualforecast);
                            if (count($decimalCount) > 1) {
                                $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                            } else {
                                $individualforecast = number_format($individualforecast, 0);
                            }
                        } else {
                            $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                            $decimalCount = explode(".", $individualforecast);
                            if (count($decimalCount) > 1) {
                                $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                            } else {
                                $individualforecast = number_format($individualforecast, 0);
                            }
                        }
                    }
                }
                $opportunity->setCurrentuserforecast($individualforecast);
            }


            return $this->render('OpportunityBundle:Default:manageOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'opportunities' => $opportunities, 'fullname' => $fullname, 'manageview' => $user->getOpportunityview()));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function manageopportunityV2Action(Request $request) {
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

            $opportunitiesArray = $this->getOpportunityArray($token, '-1', '');
//return $this->render('OpportunityBundle:Default:test.html.twig');
            return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'opportunitiesArray' => $opportunitiesArray, 'fullname' => $fullname,
                        'manageview' => $user->getOpportunityview(), 'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function opportunitycontactfilterV2Action(Request $request, $id, $filter) {
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

            $opportunitiesArray = $this->getOpportunityArray($token, $id, $filter);
            //return $this->render('OpportunityBundle:Default:test.html.twig');
            return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', array('name' => $token->getUsername(),
                        'role' => $token->getRole(), 'opportunitiesArray' => $opportunitiesArray, 'fullname' => $fullname,
                        'manageview' => $user->getOpportunityview(), 'contactid' => $id, 'filter' => $filter, 'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    private function checkOwnDeal($username, $opportunity) {
        if ($opportunity->getUsername() === $username) {
            return true;
        } else {
            return false;
        }
    }

    private function checkSharedDeal($username, $opportunity) {
        $usernames = explode(":", $opportunity->getSharing());
        foreach ($usernames as $tempUsername) {
            if ($tempUsername === $username) {
                return true;
            }
        }
        return false;
    }

    private function getOpportunityArray($token, $id, $filter) {
        $em = $this->getDoctrine()->getManager();

        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $repository2 = $em->getRepository("SettingsBundle:Stages");
        $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
        $repository4 = $em->getRepository("SettingsBundle:Producttypes");
        $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $user = $repository->findOneBy(array('username' => $token->getUsername()));
        if ($id == '-1') {
            $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
        } else {
            if ($filter == "Won") {
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active', 'contactid' => $id, 'stage' => 'won'));
            } else if ($filter == "Lost") {
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active', 'contactid' => $id, 'stage' => 'lost'));
            } else if ($filter == "Open") {
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active', 'contactid' => $id));
                $tempOpportunities = array();
                foreach ($opportunities as $opportunity) {
                    if ($opportunity->getStage() !== 'won' && $opportunity->getStage() !== 'lost') {
                        array_push($tempOpportunities, $opportunity);
                    }
                }
                $opportunities = $tempOpportunities;
            } else if ($filter == "WonUser") {
                $requestUser = $repository->findOneBy(array('id' => $id));
                $opportunities = $repository1->findBy(array('ownedcompany' => $requestUser->getCompanyname(), 'status' => 'Active', 'stage' => 'won'));
                $tempOpportunities = array();
                foreach ($opportunities as $opportunity) {
                    if ($this->checkOwnDeal($requestUser->getUsername(), $opportunity) || $this->checkSharedDeal($requestUser->getUsername(), $opportunity)) {
                        array_push($tempOpportunities, $opportunity);
                    }
                }
                $opportunities = $tempOpportunities;
            } else if ($filter == "LostUser") {
                $requestUser = $repository->findOneBy(array('id' => $id));
                $opportunities = $repository1->findBy(array('ownedcompany' => $requestUser->getCompanyname(), 'status' => 'Active', 'stage' => 'lost'));
                $tempOpportunities = array();
                foreach ($opportunities as $opportunity) {
                    if ($this->checkOwnDeal($requestUser->getUsername(), $opportunity) || $this->checkSharedDeal($requestUser->getUsername(), $opportunity)) {
                        array_push($tempOpportunities, $opportunity);
                    }
                }
                $opportunities = $tempOpportunities;
            } else if ($filter == "OpenUser") {
                $requestUser = $repository->findOneBy(array('id' => $id));
                $opportunities = $repository1->findBy(array('ownedcompany' => $requestUser->getCompanyname(), 'status' => 'Active'));
                $tempOpportunities = array();
                foreach ($opportunities as $opportunity) {
                    if ($opportunity->getStage() !== 'won' && $opportunity->getStage() !== 'lost' &&
                            ($this->checkOwnDeal($requestUser->getUsername(), $opportunity) || $this->checkSharedDeal($requestUser->getUsername(), $opportunity))) {
                        array_push($tempOpportunities, $opportunity);
                    }
                }
                $opportunities = $tempOpportunities;
            }
        }

        $opportunitiesArray = array();

        foreach ($opportunities as $opportunity) {

            $contactId = $opportunity->getContactid();
            $contactIdOpportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active', 'contactid' => $contactId));
            $wondealCount = 0;
            $wondealAmount = 0;
            $lossdealCount = 0;
            $lossdealAmount = 0;
            $opendealCount = 0;
            $opendealAmout = 0;

            foreach ($contactIdOpportunities as $contactOpportunity) {
                if ($contactOpportunity->getStage() == 6) {
                    $wondealCount++;
                    $wondealAmount += str_replace(",", "", $contactOpportunity->getRevenue());
                } else if ($contactOpportunity->getStage() == 7) {
                    $lossdealCount++;
                    $lossdealAmount += str_replace(",", "", $contactOpportunity->getProjectedrevenue());
                } else {
                    $opendealCount++;
                    $opendealAmout += str_replace(",", "", $contactOpportunity->getForecast());
                }
            }

            $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
            $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
            $opportunity->setOwnerfullname($ownedUserfullname);
            $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
            if ($stage) {
                $opportunity->setChanceofsale($stage->getSalechance());
                $opportunity->setStage($stage->getName());
            }
            if ($opportunity->getStage() == 'won') {
                $opportunity->setChanceofsale('100');
                $opportunity->setStage('Won');
            }
            if ($opportunity->getStage() == 'lost') {
                $opportunity->setChanceofsale('0');
                $opportunity->setStage('Lost');
            }
            $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
            if ($accounttype) {
                $opportunity->setAccounttype($accounttype->getName());
            }

            $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
            if ($producttype) {
                $opportunity->setProducttype($producttype->getName());
            }

            $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
            if ($opportunitysource) {
                $opportunity->setOpportunitysource($opportunitysource->getName());
            }


            $sharinguserstring = $opportunity->getSharing();
            $sharedusers = explode(":", $sharinguserstring);
            $sharedPercentage = 0;
            $individualforecast = -1;

            if ($sharinguserstring != '') {

                foreach ($sharedusers as $shareduser) {

                    $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                    if ($sharingUser) {
                        $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                    }
                }

                if ($opportunity->getUsername() == $token->getUsername()) {
                    if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                        $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                        //$individualforecast = 1200;
                        $decimalCount = explode(".", $individualforecast);
                        //echo count($decimalCount);
                        //exit;
                        if (count($decimalCount) > 1) {
                            $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                        } else {
                            $individualforecast = number_format($individualforecast, 0);
                        }
                    } else {
                        $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                        $decimalCount = explode(".", $individualforecast);
                        if (count($decimalCount) > 1) {
                            $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                        } else {
                            $individualforecast = number_format($individualforecast, 0);
                        }
                    }
                } else if (in_array($token->getUsername(), $sharedusers)) {
                    if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                        $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                        $decimalCount = explode(".", $individualforecast);
                        if (count($decimalCount) > 1) {
                            $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                        } else {
                            $individualforecast = number_format($individualforecast, 0);
                        }
                    } else {
                        $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                        $decimalCount = explode(".", $individualforecast);
                        if (count($decimalCount) > 1) {
                            $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                        } else {
                            $individualforecast = number_format($individualforecast, 0);
                        }
                    }
                }
            }
            $opportunity->setCurrentuserforecast($individualforecast);

            $arrElement["id"] = $opportunity->getId();
            $arrElement["name"] = $opportunity->getPersonname();
            $arrElement["company"] = $opportunity->getOrganizationname();
            $arrElement["product_type"] = $opportunity->getProducttype();
            $arrElement["stage"] = $opportunity->getStage();
            $arrElement["weighted_revenue"] = $opportunity->getCurrentuserforecast();
            $arrElement["weighted_revenue_all"] = $opportunity->getForecast();
            $arrElement["projected_revenue"] = $opportunity->getProjectedrevenue();
            $arrElement["expected_closed_date"] = $opportunity->getCloseddate();
            $arrElement["owner"] = $opportunity->getOwnerfullname();
            $arrElement["username"] = $opportunity->getUsername();
            $arrElement["partnername"] = $opportunity->getPartnername();
            $arrElement["partnerpercentage"] = $opportunity->getPartnerpercentage();

//            $arrElement["email"] = $opportunity->getAnnualdraw();
//            $arrElement["telephone"] = $opportunity->getEarninggoal();
//            $arrElement["tags"] = $opportunity->getOpendealcount();
            $arrElement["sharedUsers"] = $opportunity->getSharing();
            $arrElement["deal_account_type"] = $opportunity->getAccounttype();
            $arrElement["deal_source"] = $opportunity->getOpportunitysource();
            $arrElement["open_deals"] = $opendealCount . ' / $' . number_format($opendealAmout);   //need to check
            $arrElement["won_deals"] = $wondealCount . ' / $' . number_format($wondealAmount);   //need to check
            $arrElement["lost_deals"] = $lossdealCount . ' / $' . number_format($lossdealAmount);   //need to check
            $arrElement["tags"] = $opportunity->getTags();   //need to check
            array_push($opportunitiesArray, $arrElement);
        }
        $opportunitiesArray = array('opportunities' => $opportunitiesArray);

        return json_encode($opportunitiesArray);
    }

    public function getOpportunityTableDataAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $id = $request->get('id');
            $filter = $request->get('filter');
            return new Response($this->getOpportunityArray($token, $id, $filter));
        } else {
            return new Response("error!!");
        }
    }

    public function updateopportunityAction(Request $request) {

        $token = $request->getSession()->get('token');
        if ($token && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
            $id = $request->get('id');
            $opportunitie = $repository1->findOneBy(array('id' => $id));
            $opportunitie->setPersonname($request->get('personname'));
            $opportunitie->setOrganizationname($request->get('organizationname'));
            $opportunitie->setProjectedrevenue($request->get('projectedrevenue'));
            $opportunitie->setAccounttype($request->get('accounttype'));
            $opportunitie->setStage($request->get('stage'));
            $opportunitie->setProducttype($request->get('producttype'));
            $opportunitie->setOpportunitysource($request->get('opportunitysource'));
            $opportunitie->setNotes($request->get('notes'));
            $opportunitie->setVisibility($request->get('visibility'));
            if ($request->get('stage') == "won" || $request->get('stage') == "lost") {
                $opportunitie->setCloseddate(date("Y-m-d"));
            } else {
                $opportunitie->setCloseddate($request->get('closeddate'));
            }
            $opportunitie->setForecast($request->get('forecast'));
            $opportunitie->setRevenue($request->get('revenue'));
            $opportunitie->setReason($request->get('reason'));
            $opportunitie->setUserrevenue($request->get('userrevenue'));
            $opportunitie->setTags($request->get('tags'));
            $opportunitie->setWonnotes($request->get('modalnoteshidden'));
            $opportunitie->setPartnername($request->get('partnername'));
            $opportunitie->setPartnerpercentage($request->get('partnerpercentage'));
            $sharingusers = '';
            $colonFlag = true;
            if ($request->get('sharingusers') != '') {
                foreach ($request->get('sharingusers') as $sharinguser) {
                    if (!$colonFlag) {
                        $sharingusers .= ':';
                    }
                    $sharingusers .= $sharinguser;
                    $colonFlag = false;
                }
            }
            $opportunitie->setSharing($sharingusers);
            try {
                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository4 = $em->getRepository("SettingsBundle:Producttypes");
                $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $image = $user->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }

                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
                foreach ($opportunities as $opportunity) {
                    $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                    $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
                    $opportunity->setOwnerfullname($ownedUserfullname);
                    $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
                    if ($stage) {
                        $opportunity->setChanceofsale($stage->getSalechance());
                        $opportunity->setStage($stage->getName());
                    }
                    if ($opportunity->getStage() == 'won') {
                        $opportunity->setChanceofsale('100');
                    }
                    if ($opportunity->getStage() == 'lost') {
                        $opportunity->setChanceofsale('0');
                    }
                    $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
                    if ($accounttype) {
                        $opportunity->setAccounttype($accounttype->getName());
                    }

                    $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
                    if ($producttype) {
                        $opportunity->setProducttype($producttype->getName());
                    }

                    $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
                    if ($opportunitysource) {
                        $opportunity->setOpportunitysource($opportunitysource->getName());
                    }


                    $sharinguserstring = $opportunity->getSharing();
                    $sharedusers = explode(":", $sharinguserstring);
                    $sharedPercentage = 0;
                    $individualforecast = -1;

                    if ($sharinguserstring != '') {

                        foreach ($sharedusers as $shareduser) {

                            $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                            if ($sharingUser) {
                                $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                            }
                        }

                        if ($opportunity->getUsername() == $token->getUsername()) {
                            if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                                //$individualforecast = 1200;
                                $decimalCount = explode(".", $individualforecast);
                                //echo count($decimalCount);
                                //exit;
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        } else if (in_array($token->getUsername(), $sharedusers)) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        }
                    }
                    $opportunity->setCurrentuserforecast($individualforecast);
                }
                $opportunitiesArray = $this->getOpportunityArray($token, '-1', '');
                return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'opportunitiesArray' => $opportunitiesArray, 'fullname' => $fullname,
                            'successmsg' => 'Well done! You succesfully update your deal.',
                            'manageview' => $user->getOpportunityview(), 'image' => $image));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository5 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Producttypes");
                $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");


                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $accounttypes = $repository5->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
                $currentCompany = $currentUser->getCompanyname();
                $users = $repository->findBy(array('companyname' => $currentCompany));
                $image = $user->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }

                return $this->render('OpportunityBundle:Default:editOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'fullname' => $fullname,
                            'errormsg' => 'Something went wrong. Try Again',
                            'opportunity' => $opportunity, 'accounttypes' => $accounttypes,
                            'stages' => $stages, 'producttypes' => $producttypes,
                            'opportunitysources' => $opportunitysources, 'users' => $users,
                            'image' => $image));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function deleteopportunityAction(Request $request, $id) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
            $opportunitie = $repository1->findOneBy(array('id' => $id));
            $opportunitie->setStatus("Inactive");
            try {

                $em->flush();
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository4 = $em->getRepository("SettingsBundle:Producttypes");
                $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
                foreach ($opportunities as $opportunity) {
                    $ownedUser = $repository->findOneBy(array('username' => $opportunity->getUsername()));
                    $ownedUserfullname = $ownedUser->getFirstname() . " " . $ownedUser->getLastname();
                    $opportunity->setOwnerfullname($ownedUserfullname);
                    $stage = $repository2->findOneBy(array('id' => $opportunity->getStage()));
                    if ($stage) {
                        $opportunity->setChanceofsale($stage->getSalechance());
                        $opportunity->setStage($stage->getName());
                    }
                    if ($opportunity->getStage() == 'won') {
                        $opportunity->setChanceofsale('100');
                    }
                    if ($opportunity->getStage() == 'lost') {
                        $opportunity->setChanceofsale('0');
                    }
                    $accounttype = $repository3->findOneBy(array('id' => $opportunity->getAccounttype()));
                    if ($accounttype) {
                        $opportunity->setAccounttype($accounttype->getName());
                    }

                    $producttype = $repository4->findOneBy(array('id' => $opportunity->getProducttype()));
                    if ($producttype) {
                        $opportunity->setProducttype($producttype->getName());
                    }

                    $opportunitysource = $repository5->findOneBy(array('id' => $opportunity->getOpportunitysource()));
                    if ($opportunitysource) {
                        $opportunity->setOpportunitysource($opportunitysource->getName());
                    }


                    $sharinguserstring = $opportunity->getSharing();
                    $sharedusers = explode(":", $sharinguserstring);
                    $sharedPercentage = 0;
                    $individualforecast = -1;

                    if ($sharinguserstring != '') {

                        foreach ($sharedusers as $shareduser) {

                            $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                            if ($sharingUser) {
                                $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                            }
                        }

                        if ($opportunity->getUsername() == $token->getUsername()) {
                            if (($sharedPercentage + $user->getCommissionoriginator()) >= 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionoriginator()) / 100;
                                //$individualforecast = 1200;
                                $decimalCount = explode(".", $individualforecast);
                                //echo count($decimalCount);
                                //exit;
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        } else if (in_array($token->getUsername(), $sharedusers)) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                            }
                        }
                    }
                    $opportunity->setCurrentuserforecast($individualforecast);
                }
                $image = $user->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }
                
                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                
                $opportunitiesArray = $this->getOpportunityArray($token, '-1', '');
                //return $this->render('OpportunityBundle:Default:test.html.twig');
                return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(), 
                        'opportunitiesArray' => $opportunitiesArray, 'fullname' => $fullname, 
                        'successmsg' => 'You succesfully delete your deal.',
                        'manageview' => $user->getOpportunityview(), 'image' => $image));
                
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository = $em->getRepository("LoginLoginBundle:Users");
                $repository5 = $em->getRepository("SettingsBundle:Accounttypes");
                $repository2 = $em->getRepository("SettingsBundle:Stages");
                $repository3 = $em->getRepository("SettingsBundle:Producttypes");
                $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");


                $user = $repository->findOneBy(array('username' => $token->getUsername()));
                $fullname = $user->getFirstname() . " " . $user->getLastname();
                $accounttypes = $repository5->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $stages = $repository2->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname(), 'status' => 'Active'));
                $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
                $currentCompany = $currentUser->getCompanyname();
                $users = $repository->findBy(array('companyname' => $currentCompany));

                $image = $user->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }

                $opportunitiesArray = $this->getOpportunityArray($token, '-1', '');
                //return $this->render('OpportunityBundle:Default:test.html.twig');
                return $this->render('OpportunityBundle:Default:manageOpportunityV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'opportunitiesArray' => $opportunitiesArray, 'fullname' => $fullname,
                            'errormsg' => 'You succesfully delete your deal.',
                            'manageview' => $user->getOpportunityview(), 'image' => $image));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

}
