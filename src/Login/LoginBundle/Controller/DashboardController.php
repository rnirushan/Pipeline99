<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DashboardController extends Controller {

    public function adduserAction(Request $request) {
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
            
            return $this->render('LoginLoginBundle:Default:addusers_v2.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(), 
                        'fullname' => $fullname, 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function importuserAction(Request $request) {
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

            return $this->render('LoginLoginBundle:Default:importusers_v2.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(), 
                        'fullname' => $fullname, 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function updateprofileAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $token = $request->getSession()->get('token');
        if ($token) {
            $adminname = $token->getUsername();
            $user = $repository->findOneBy(array('username' => $adminname));
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $earn = $user->getEarninggoal();
            if ($earn != null) {
                $earn = str_replace(',', '', $earn);
            } else {
                $earn = '';
            }
            $annual = $user->getAnnualdraw();
            if ($annual != null) {
                $annual = str_replace(',', '', $annual);
            } else {
                $annual = '';
            }
            $tele = $user->getTelephoneoffice();
            if ($tele != null) {
                $tele = str_replace('(', '', $tele);
                $tele = str_replace(')', '', $tele);
                $tele = str_replace('-', '', $tele);
                $tele = str_replace(' ', '', $tele);
                $tele = str_replace('Ext.', '', $tele);
            } else {
                $tele = '';
            }
            $mobile = $user->getTelephonemobile();
            if ($mobile != null) {
                $mobile = str_replace('(', '', $mobile);
                $mobile = str_replace(')', '', $mobile);
                $mobile = str_replace('-', '', $mobile);
                $mobile = str_replace(' ', '', $mobile);
            } else {
                $mobile = '';
            }
            $fullname = $user->getFirstname() . " " . $user->getLastname();

            return $this->render('LoginLoginBundle:Default:editusers_v2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'user' => $user, 'earn' => $earn, 'annual' => $annual, 'tele' => $tele,
                        'mobile' => $mobile, 'update' => 'true', 'fullname' => $fullname,
                        'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function uploadAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $session = $request->getSession();
        $admin = $session->get('token');
        $adminname = $admin->getUsername();
        $adminuser = $repository->findOneBy(array('username' => $adminname));

        $industry = $adminuser->getIndustry();
        $companysize = $adminuser->getCompanysize();
        $companyName = $adminuser->getCompanyname();
        $file = $request->files->get('file');
        $fileOpen = fopen($file, "r");
        $data = array();

        $flag = true;
        $usernameError = 'username;';
        while (($data = fgetcsv($fileOpen, 1000, ",")) !== FALSE) {
            if ($flag) {
                $flag = false;
                continue;
            }
            $exist = $repository->findOneBy(array('username' => $data[2]));
            if ($exist) {
                $usernameError .= $data[2] . ";";
                continue;
            }
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < 10; $i++) {
                $key .= $keys[array_rand($keys)];
            }

            $password = sha1($key);

            $user = new Users();

            $user->setFirstname($data[0]);
            $user->setLastname($data[1]);
            $user->setUsername($data[2]);
            $user->setEmail($data[3]);
            $user->setTelephoneoffice($data[4]);
            $user->setCommissionoriginator($data[5]);
            $user->setCommissionnonoriginator($data[6]);
            $user->setEarninggoal($data[7]);
            $user->setIndustry($industry);
            $user->setCompanysize($companysize);
            $user->setRole("Regular");
            $user->setPassword($password);
            $user->setCompanyname($companyName);
            $user->setStatus("Active");

            try {
                $em->persist($user);
                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                        ->setSubject('Hello Email')
                        ->setFrom('sarakanna1@gmail.com')
                        ->setTo($data[3])
                        ->setBody(
                        "welcome to pipeline. your username is " . $data[2] . " and your password is " . $key . "."
                        )
                ;
                $mailer->send($message);
                $em->flush();
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('LoginLoginBundle:Default:importUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'errormsg' => 'Invalid Arguments. Try Again'));
            }
        }
        if ($usernameError == 'username;') {
            return new Response('Success');
        } else {
            return new Response($usernameError);
        }
    }

    public function fullsignupAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $session = $request->getSession();
        $admin = $session->get('token');

        if ($request->getMethod() == 'POST' && ($admin->getRole() == 'Admin' || $admin->getRole() == 'Master Admin')) {
            $profileimage = $request->files->get('img');

            if ($profileimage != null && $profileimage->isValid()) {
                $profilestore = $profileimage->move("bundles/loginlogin/images", $request->get('username') . "." . split('/', $profileimage->getMimeType())[1]);
            } else {
                $profilestore = '';
            }

            $username = $request->get('username');
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $email = $request->get('email');
            $dob = $request->get('dob');
            $officetel = $request->get('officetel');
            $mobile = $request->get('mobile');
            $jobtitle = $request->get('jobtitle');

            $address1 = $request->get('address1');
            $address2 = $request->get('address2');
            $city = $request->get('city');
            $state = $request->get('state');
            $postalcode = $request->get('postalcode');
            $empstartdate = $request->get('empstartdate');
            $commisionoriginator = $request->get('commisionoriginator');
            $commisionnonoriginator = $request->get('commisionnonoriginator');
            $annualdraw = $request->get('annualdraw');
            $earninggoals = $request->get('earninggoals');
            $profilenote = $request->get('profilenote');
            $linkedin = $request->get('linkedin');
            $twitter = $request->get('twitter');
            $role = 'Regular';
            $status = 'Active';

            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < 10; $i++) {
                $key .= $keys[array_rand($keys)];
            }

            $password = sha1($key);


            $adminname = $admin->getUsername();
            $adminuser = $repository->findOneBy(array('username' => $adminname));
            $industry = $adminuser->getIndustry();
            $companysize = $adminuser->getCompanysize();
            $companyname = $adminuser->getCompanyname();
            $user = new Users();

            $user->setUsername($username);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setDob($dob);
            $user->setTelephoneoffice($officetel);
            $user->setTelephonemobile($mobile);
            $user->setJobtitle($jobtitle);
            $user->setCompanyname($companyname);
            $user->setAddress1($address1);
            $user->setAddress2($address2);
            $user->setCity($city);
            $user->setState($state);
            $user->setPostalcode($postalcode);
            $user->setContractstartdate($empstartdate);
            $user->setCommissionoriginator($commisionoriginator);
            $user->setCommissionnonoriginator($commisionnonoriginator);
            $user->setAnnualdraw($annualdraw);
            $user->setEarninggoal($earninggoals);
            $user->setProfilenote($profilenote);
            $user->setLinkedin($linkedin);
            $user->setTwitter($twitter);
            $user->setRole($role);
            $user->setIndustry($industry);
            $user->setCompanysize($companysize);
            $user->setPassword($password);
            $user->setStatus($status);
            $user->setImage($profilestore);
            $user->setUserview(10);
            $user->setContactview(10);
            $user->setOpportunityview(10);
            $fullname = $adminuser->getFirstname() . " " . $adminuser->getLastname();
            try {
                $em->persist($user);
                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                        ->setSubject('Hello Email')
                        ->setFrom('sarakanna1@gmail.com')
                        ->setTo($email)
                        ->setBody(
                        "welcome to pipeline. your username is " . $username . " and your password is " . $key . "."
                        )
                ;
                $mailer->send($message);
                $em->flush();

                $userArray = $this->getUserArray($admin);
                return $this->redirectToRoute('settings_pipelinesetup');
                //return $this->render('LoginLoginBundle:Default:manageUsersV2.html.twig', array('name' => $admin->getUsername(), 'userArray' => $userArray, 'role' => $admin->getRole(), 'fullname' => $fullname, 'manageview' => $adminuser->getUserView(), 'successmsg' => 'Regular User Created'));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('LoginLoginBundle:Default:addUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again'));
            }
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need Admin privillages to do this addition.'));
        }
    }

    public function getcompanyajaxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $session = $request->getSession();
        $admin = $session->get('token');
        $adminname = $admin->getUsername();
        $adminuser = $repository->findOneBy(array('username' => $adminname));
        $companyName = $adminuser->getCompanyname();
        $address1 = $adminuser->getAddress1();
        $address2 = $adminuser->getAddress2();
        $city = $adminuser->getCity();
        $state = $adminuser->getState();
        $postalcode = $adminuser->getPostalcode();

        $response = array("companyName" => $companyName, "address1" => $address1, "address2" => $address2, "city" => $city, "state" => $state, "postalcode" => $postalcode);
        return new Response(json_encode($response));
    }

    public function userTableDataAction(Request $request) {

        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");

        $admin = $repository->findOneBy(array('username' => $token->getUsername()));
        $role = $token->getRole();
        if ($role == 'Admin' || $role == 'Regular') {

            $admin_companyName = $admin->getCompanyname();

            $users = $repository->findBy(array('companyname' => $admin_companyName, 'status' => 'Active'));
            foreach ($users as $user) {
                $wonAmount = 0;
                $lossAmount = 0;
                $originOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active'));
                $ownedOpportunityCount = count($originOpportunities);
                $wonOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active', 'stage' => 'won'));
                $wonOpportunityCount = count($wonOpportunities);
                foreach ($wonOpportunities as $wonOpportunity) {
                    $wonAmount += intval(str_replace(",", "", $wonOpportunity->getRevenue()));
                }
                $lossOpportunities = $repository1->findBy(array('username' => $user->getUsername(), 'status' => 'Active', 'stage' => 'lost'));
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
                                if ($opportunity->getStage() == 'won') {
                                    $wonCount++;
                                    $wonAmount += intval(str_replace(",", "", $opportunity->getRevenue()));
                                }
                                if ($opportunity->getStage() == 'lost') {
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

            $response = array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $userArray, 'fullname' => $fullname, 'manageview' => $admin->getUserview());
            //echo json_encode($response);exit;
            return new Response(json_encode($response));
            //return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(),'role' => $admin->getRole(),'users' => $users,'fullname'=> $fullname,'manageview'=>$admin->getUserview())); 
        } else {
            return new Response("error");
            //return $this->render('LoginLoginBundle:Default:signIn.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    private function getUserArray($token) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $admin = $repository->findOneBy(array('username' => $token->getUsername()));
        $role = $token->getRole();

        $admin_companyName = $admin->getCompanyname();

        $users = $repository->findBy(array('companyname' => $admin_companyName, 'status' => 'Active'));

        $userArray = array();
        foreach ($users as $tempUser) {
            $wonAmount = 0;
            $lossAmount = 0;
            $originOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active'));
            $ownedOpportunityCount = count($originOpportunities);
            $wonOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'won'));
            $wonOpportunityCount = count($wonOpportunities);
            foreach ($wonOpportunities as $wonOpportunity) {
                $wonAmount += intval(str_replace(",", "", $wonOpportunity->getRevenue()));
            }
            $lossOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'lost'));
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
                        if (strtolower($tempUser->getUsername()) == strtolower($sharedname)) {
                            $totalCount++;
                            if ($opportunity->getStage() == 'won') {
                                $wonCount++;
                                $wonAmount += intval(str_replace(",", "", $opportunity->getRevenue()));
                            }
                            if ($opportunity->getStage() == 'lost') {
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
            $tempUser->setWondealcount(number_format($wonAmount));
            $tempUser->setLossdealcount(number_format($lossAmount));
            $tempUser->setOpendealcount($totalOpportunityCount - $totalWonOpportunityCount - $totalLossOpportunityCount);

            $tempUser = $this->revenueAndForecastCalculation($em, $tempUser);

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

        $response = array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $userArray, 'manageview' => $admin->getUserview());
        return json_encode($response);
    }

    public function usersAction(Request $request) {

        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $admin = $repository->findOneBy(array('username' => $token->getUsername()));
        $role = $token->getRole();

        $userArray = array();
        if ($role == 'Admin' || $role == 'Regular') {

            $admin_companyName = $admin->getCompanyname();

            $users = $repository->findBy(array('companyname' => $admin_companyName, 'status' => 'Active'));

            $userArray = array();
            foreach ($users as $tempUser) {
                $wonAmount = 0;
                $lossAmount = 0;
                $originOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active'));
                $ownedOpportunityCount = count($originOpportunities);
                $wonOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'won'));
                $wonOpportunityCount = count($wonOpportunities);
                foreach ($wonOpportunities as $wonOpportunity) {
                    $wonAmount += intval(str_replace(",", "", $wonOpportunity->getRevenue()));
                }
                $lossOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'lost'));
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
                            if (strtolower($tempUser->getUsername()) == strtolower($sharedname)) {
                                $totalCount++;
                                if ($opportunity->getStage() == 'won') {
                                    $wonCount++;
                                    $wonAmount += intval(str_replace(",", "", $opportunity->getRevenue()));
                                }
                                if ($opportunity->getStage() == 'lost') {
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
                $tempUser->setWondealcount(number_format($wonAmount));
                $tempUser->setLossdealcount(number_format($lossAmount));
                $tempUser->setOpendealcount($totalOpportunityCount - $totalWonOpportunityCount - $totalLossOpportunityCount);

                $tempUser = $this->revenueAndForecastCalculation($em, $tempUser);

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
        } else if ($role == 'Master Admin') {
            $users = $repository->findAll();

            $userArray = array();
            foreach ($users as $tempUser) {
                $tempUser = $this->revenueAndForecastCalculation($em, $tempUser);
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
        }

        $response = array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $userArray, 'manageview' => $admin->getUserview());
        //echo json_encode($response);exit;
        if ($admin) {
            $fullname = $admin->getFirstname() . " " . $admin->getLastname();
            $image = $admin->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }
            return $this->render('LoginLoginBundle:Default:manageUsersV2.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(),
                        'users' => $users, 'userArray' => json_encode($response),
                        'fullname' => $fullname, 'manageview' => $admin->getUserview(),
                        'image' => $image));
            //return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(),'role' => $admin->getRole(),'users' => $users, 'userArray'=> $userArray ,'fullname'=> $fullname,'manageview'=>$admin->getUserview())); 
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function editsingleuserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->find($id);
        $token = $request->getSession()->get('token');
        $earn = $user->getEarninggoal();
        if ($earn != null) {
            $earn = str_replace(',', '', $earn);
        } else {
            $earn = '';
        }
        $annual = $user->getAnnualdraw();
        if ($annual != null) {
            $annual = str_replace(',', '', $annual);
        } else {
            $annual = '';
        }
        $tele = $user->getTelephoneoffice();
        if ($tele != null) {
            $tele = str_replace('(', '', $tele);
            $tele = str_replace(')', '', $tele);
            $tele = str_replace('-', '', $tele);
            $tele = str_replace(' ', '', $tele);
            $tele = str_replace('Ext.', '', $tele);
        } else {
            $tele = '';
        }
        $mobile = $user->getTelephonemobile();
        if ($mobile != null) {
            $mobile = str_replace('(', '', $mobile);
            $mobile = str_replace(')', '', $mobile);
            $mobile = str_replace('-', '', $mobile);
            $mobile = str_replace(' ', '', $mobile);
        } else {
            $mobile = '';
        }

        if ($token) {
            $currentuser = $repository->findOneBy(array('username' => $token->getUsername()));
            $image = $currentuser->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $fullname = $currentuser->getFirstname() . " " . $currentuser->getLastname();
            return $this->render('LoginLoginBundle:Default:editusers_v2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'user' => $user, 'earn' => $earn, 'annual' => $annual,
                        'tele' => $tele, 'mobile' => $mobile, 'fullname' => $fullname,
                        'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function singleupdateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $session = $request->getSession();
        $admin = $session->get('token');
        if ($admin) {
            $id = $request->get('id');
            $currentuser = $repository->findOneBy(array('id' => $id));
            $adminname = $admin->getUsername();
            $adminuser = $repository->findOneBy(array('username' => $adminname));
            $fullname = $adminuser->getFirstname() . " " . $adminuser->getLastname();
            if ($request->getMethod() == 'POST' && ($admin->getRole() == 'Admin' || $admin->getRole() == 'Master Admin' || $id == $adminuser->getId())) {

                $profileimage = $request->files->get('img');

                if ($profileimage != null && $profileimage->isValid()) {
                    $profilestore = $profileimage->move("bundles/loginlogin/images", $request->get('username') . "." . split('/', $profileimage->getMimeType())[1]);
                } else {
                    $profilestore = '';
                }

                $password = $currentuser->getPassword();
                $currentRole = $currentuser->getRole();

                $username = $request->get('username');
                $firstname = $request->get('firstname');
                $lastname = $request->get('lastname');
                $email = $request->get('email');
                $dob = $request->get('dob');
                $officetel = $request->get('officetel');
                $mobile = $request->get('mobile');
                $jobtitle = $request->get('jobtitle');

                $address1 = $request->get('address1');
                $address2 = $request->get('address2');
                $city = $request->get('city');
                $state = $request->get('state');
                $postalcode = $request->get('postalcode');
                $empstartdate = $request->get('empstartdate');
                $commisionoriginator = $request->get('commisionoriginator');
                $commisionnonoriginator = $request->get('commisionnonoriginator');
                $annualdraw = $request->get('annualdraw');
                $earninggoals = $request->get('earninggoals');
                $profilenote = $request->get('profilenote');
                $linkedin = $request->get('linkedin');
                $twitter = $request->get('twitter');
                $companyname = $request->get('companyname');
                $role = $request->get('role');

                if ($currentRole == 'Master Admin') {
                    $role = 'Master Admin';  //this is because Master admin role never changed from user
                }
                if ($admin->getRole() == 'Regular') {
                    $role = 'Regular';
                }

                $newPassword = $request->get('password');

                if ($newPassword != null) {
                    $password = sha1($newPassword);
                }


                if ($adminuser) {
                    $industry = $adminuser->getIndustry();
                    $companysize = $adminuser->getCompanysize();
                    if ($companyname == null) {
                        $companyname = $adminuser->getCompanyname();
                        if ($companyname == null) {
                            $companyname = '';
                        }
                    }
                } else {
                    $industry = '';
                    $companysize = '';
                    if ($companyname == null) {
                        $companyname = '';
                    }
                }

                if ($adminuser->getRole() != 'Master Admin' && $currentRole == 'Regular' && $role == 'Admin') {
                    $adminuser->setRole('Regular');
                    $em->flush();
                }

                $currentuser->setUsername($username);
                $currentuser->setFirstname($firstname);
                $currentuser->setLastname($lastname);
                $currentuser->setEmail($email);
                $currentuser->setDob($dob);
                $currentuser->setTelephoneoffice($officetel);
                $currentuser->setTelephonemobile($mobile);
                $currentuser->setJobtitle($jobtitle);
                $currentuser->setCompanyname($companyname);
                $currentuser->setAddress1($address1);
                $currentuser->setAddress2($address2);
                $currentuser->setCity($city);
                $currentuser->setState($state);
                $currentuser->setPostalcode($postalcode);
                $currentuser->setContractstartdate($empstartdate);
                $currentuser->setCommissionoriginator($commisionoriginator);
                $currentuser->setCommissionnonoriginator($commisionnonoriginator);
                $currentuser->setAnnualdraw($annualdraw);
                $currentuser->setEarninggoal($earninggoals);
                $currentuser->setProfilenote($profilenote);
                $currentuser->setLinkedin($linkedin);
                $currentuser->setTwitter($twitter);
                $currentuser->setRole($role);
                $currentuser->setIndustry($industry);
                $currentuser->setCompanysize($companysize);
                $currentuser->setPassword($password);
                $currentuser->setStatus("Active");
                $currentuser->setImage($profilestore);

                try {

                    $em->flush();
                    if ($admin->getRole() == 'Admin' || $admin->getRole() == 'Regular') {
                        $users = $repository->findBy(array('companyname' => $companyname, 'status' => 'Active'));
                    } else {
                        $users = $repository->findAll();
                    }

                    $userArray = array();
                    foreach ($users as $tempUser) {
                        $wonAmount = 0;
                        $lossAmount = 0;
                        $originOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active'));
                        $ownedOpportunityCount = count($originOpportunities);
                        $wonOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'won'));
                        $wonOpportunityCount = count($wonOpportunities);
                        foreach ($wonOpportunities as $wonOpportunity) {
                            $wonAmount += intval(str_replace(",", "", $wonOpportunity->getRevenue()));
                        }
                        $lossOpportunities = $repository1->findBy(array('username' => $tempUser->getUsername(), 'status' => 'Active', 'stage' => 'lost'));
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
                                    if (strtolower($tempUser->getUsername()) == strtolower($sharedname)) {
                                        $totalCount++;
                                        if ($opportunity->getStage() == 'won') {
                                            $wonCount++;
                                            $wonAmount += intval(str_replace(",", "", $opportunity->getRevenue()));
                                        }
                                        if ($opportunity->getStage() == 'lost') {
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
                        $tempUser->setWondealcount(number_format($wonAmount));
                        $tempUser->setLossdealcount(number_format($lossAmount));
                        $tempUser->setOpendealcount($totalOpportunityCount - $totalWonOpportunityCount - $totalLossOpportunityCount);

                        $tempUser = $this->revenueAndForecastCalculation($em, $tempUser);
                        $tempUser = $this->revenueAndForecastCalculation($em, $tempUser);
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
                    $response = array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $userArray, 'manageview' => $adminuser->getUserview());
                    $response = json_encode($response);
                    //return $this->redirectToRoute('settings_pipelinesetup');
                    $image = $currentuser->getImage();
                    if ($image == '' || $image == null) {
                        $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                    }

                    return $this->render('LoginLoginBundle:Default:manageUsersV2.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(),
                                'userArray' => $response, 'fullname' => $fullname,
                                'successmsg' => "Well done ! You successfully updated " . $currentuser->getUsername() . "'s profile",
                                'manageview' => $adminuser->getUserview(), 'image' => $image));
                } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                    if ($admin->getRole() == 'Admin' || $admin->getRole() == 'Regular') {
                        $users = $repository->findBy(array('companyname' => $companyname, 'status' => 'Active'));
                        foreach ($users as $individualuser) {
                            $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                        }
                    } else {
                        $users = $repository->findAll();
                        foreach ($users as $individualuser) {
                            $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                        }
                    }
                    return $this->render('LoginLoginBundle:Default:manageUsersV2.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $users, 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again', 'manageview' => $adminuser->getUserview()));
                }
            } else {
                return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need an Admin login to Proceed'));
            }
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function deleteuserAction(Request $request, $id) {

        $admin = $request->getSession()->get('token');
        if ($admin->getRole() == 'Admin') {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $currentuser = $repository->findOneBy(array('id' => $id));
            $currentuser->setStatus("Inactive");
            $adminuser = $repository->findOneBy(array('username' => $admin->getUsername()));
            $fullname = $adminuser->getFirstname() . " " . $adminuser->getLastname();
            $companyname = $adminuser->getCompanyname();
            try {

                $em->flush();
                $users = $repository->findBy(array('companyname' => $companyname, 'status' => 'Active'));
                foreach ($users as $individualuser) {
                    $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                }
                return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $users, 'fullname' => $fullname, 'successmsg' => "Well done ! You successfully delete " . $currentuser->getUsername() . "'s profile", 'manageview' => $admin->getUserview()));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $users = $repository->findBy(array('companyname' => $companyname, 'status' => 'Active'));
                foreach ($users as $individualuser) {
                    $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                }
                return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $users, 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again', 'manageview' => $admin->getUserview()));
            }
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need an Admin login to Proceed'));
        }
    }

    /*
      public function profileAction(Request $request){
      $session = $request->getSession();
      $currentuser = $session->get('token');
      $username = $currentuser->getUsername();
      $password = $currentuser->getPassword();

      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository("LoginLoginBundle:Users");
      $user = $repository->findOneBy(array('username' => $username,'password' => $password));
      $earn = $user->getEarninggoal();
      if($earn != null){
      $earn = str_replace(',','', $earn);
      }else{
      $earn = '';
      }
      $annual = $user->getAnnualdraw();
      if($annual != null){
      $annual = str_replace(',','', $annual);
      }else{
      $annual = '';
      }
      $tele = $user->getTelephoneoffice();
      if($tele != null){
      $tele = str_replace('(','', $tele);
      $tele = str_replace(')','', $tele);
      $tele = str_replace('-','', $tele);
      $tele = str_replace(' ','', $tele);
      $tele = str_replace('Ext.','', $tele);
      }else{
      $tele = '';
      }
      $mobile = $user->getTelephonemobile();
      if($mobile != null){
      $mobile = str_replace('(','', $mobile);
      $mobile = str_replace(')','', $mobile);
      $mobile = str_replace('-','', $mobile);
      $mobile = str_replace(' ','', $mobile);

      }else{
      $mobile = '';
      }
      if($user){
      return $this->render('LoginLoginBundle:Default:editSingleUser.html.twig', array('name' => $currentuser->getUsername(),'role' => $currentuser->getRole(),'user' => $user,'earn' => $earn , 'annual' => $annual , 'tele' => $tele , 'mobile' => $mobile,'from'=>'myprofile'));

      }else{
      $session->invalidate();
      return $this->render('LoginLoginBundle:Default:login.html.twig', array('errormsg' => 'Something went wrong,Please login again.'));
      }


      } */

    public function activateAction(Request $request, $id) {
        $session = $request->getSession();
        $admin = $session->get('token');
        if ($admin && $admin->getRole() == 'Master Admin') {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $adminuser = $repository->findOneBy(array('username' => $admin->getUsername()));
            $fullname = $adminuser->getFirstname() . " " . $adminuser->getLastname();
            $user = $repository->find($id);
            $user->setStatus('Active');
            try {
                $em->flush();
                $users = $repository->findAll();
                foreach ($users as $individualuser) {
                    $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                }
                return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $users, 'fullname' => $fullname, 'successmsg' => "Well done ! you successfully reactivated " . $user->getUsername() . "'s account", 'manageview' => $admin->getUserview()));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $users = $repository->findAll();
                foreach ($users as $individualuser) {
                    $individualuser = $this->revenueAndForecastCalculation($em, $individualuser);
                }
                return $this->render('LoginLoginBundle:Default:manageUsers.html.twig', array('name' => $admin->getUsername(), 'role' => $admin->getRole(), 'users' => $users, 'fullname' => $fullname, 'errormsg' => 'Something went wrong,Try Again', 'manageview' => $admin->getUserview()));
            }
        } else {
            $session->invalidate();
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Only Master Admin is allowed to do this action '));
        }
    }

    public function selecteduseropportunitiesAction(Request $request, $username) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $username));
        $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
        $repository2 = $em->getRepository("SettingsBundle:Stages");
        $repository3 = $em->getRepository("SettingsBundle:Accounttypes");
        $repository4 = $em->getRepository("SettingsBundle:Producttypes");
        $repository5 = $em->getRepository("SettingsBundle:Opportunitysources");

        $session = $request->getSession();
        $token = $session->get('token');
        if ($token) {
            $currentuser = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentuser->getFirstname() . " " . $currentuser->getLastname();
            $opportunities = $repository1->findBy(array('ownedcompany' => $user->getCompanyname(), 'status' => 'Active'));
            $selectedopportunities = array();
            $count = 0;
            if ($opportunities) {

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

                    $isselected = false;
                    $shareduserstring = $opportunity->getSharing();
                    $sharedusers = explode(":", $shareduserstring);
                    $sharedPercentage = 0;
                    $individualforecast = -1;
                    if ($shareduserstring != '') {
                        foreach ($sharedusers as $shareduser) {

                            $sharingUser = $repository->findOneBy(array('username' => $shareduser));
                            if ($sharingUser) {
                                $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                            }
                        }

                        if (strtolower($opportunity->getUsername()) == strtolower($user->getUsername())) {
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
                                $isselected = true;
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionoriginator() + (100 - ($sharedPercentage + $user->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                                $isselected = true;
                            }
                        } else if (in_array(strtolower($user->getUsername()), array_map('strtolower', $sharedusers))) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($user->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                                $isselected = true;
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $user->getCommissionnonoriginator()) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                                $isselected = true;
                            }
                        }
                    } else {
                        if (strtolower($opportunity->getUsername()) == strtolower($user->getUsername())) {
                            $isselected = true;
                        }
                    }
                    $opportunity->setCurrentuserforecast($individualforecast);
                    if ($isselected) {
                        $selectedopportunities[$count] = $opportunity;
                        $count++;
                    }
                }
                return $this->render('OpportunityBundle:Default:manageOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'opportunities' => $selectedopportunities, 'fullname' => $fullname, 'manageview' => $currentuser->getOpportunityview()));
            }
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
                        if ($sharingUser) {
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

    public function saveconfigAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $user = $repository->findOneBy(array('username' => $token->getUsername()));

        $name = $request->get('name');
        $value = $request->get('value');

        if ($name == 'userview') {
            $user->setUserview($value);
            $em->flush();
        } else if ($name == 'contactview') {
            $user->setContactview($value);
            $em->flush();
        } else if ($name == 'opportunityview') {
            $user->setOpportunityview($value);
            $em->flush();
        }

        return new Response('Success');
    }

}
