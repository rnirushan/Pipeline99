<?php

namespace ContactsContactsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\FileBag;
use ContactsContactsBundle\Entity\Contacts;
use ContactsContactsBundle\Modals\ExposedContact;

class ContactsController extends Controller {

    public function indexAction($name) {
        return $this->render('ContactsContactsBundle:Default:index.html.twig', array('name' => $name));
    }

    public function managecontactsAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $currentCompany = $user->getCompanyname();
            $repository = $em->getRepository("ContactsContactsBundle:Contacts");
            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }


            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'contacts' => $contacts, 'fullname' => $fullname, 'manageview' => $user->getContactview()));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function managecontactsV2Action(Request $request) {

        $session = $request->getSession();
        $token = $session->get('token');

        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $response = $this->getContactData($token);

            return $this->render('ContactsContactsBundle:Default:manageContactV2.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'contactArray' => $response, 'fullname' => $fullname,
                        'manageview' => $user->getContactview(), 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function activateContactV2Action(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");

        if ($token) {
            $user = $userRepository->findOneBy(array('username' => $token->getUsername()));
            if ($user) {
                $id = $request->get("id");
                $contact = $contactRepository->findOneBy(array('id' => $id));
                if ($contact) {
                    $contact->setStatus("Active");
                    try {

                        $em->flush();
                        return new Response("true");
                    } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                        return new Response("false");
                    }
                } else {
                    return new Response("false");
                }
            } else {
                return new Response("false");
            }
        } else {
            return new Response("false");
        }
    }

    public function deleteContactV2Action(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");

        if ($token) {
            $user = $userRepository->findOneBy(array('username' => $token->getUsername()));
            if ($user) {
                $id = $request->get("id");
                $contact = $contactRepository->findOneBy(array('id' => $id));
                if ($contact) {
                    $contact->setStatus("Inactive");
                    try {

                        $em->flush();
                        return new Response("true");
                    } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                        return new Response("false");
                    }
                } else {
                    return new Response("false");
                }
            } else {
                return new Response("false");
            }
        } else {
            return new Response("false");
        }
    }

    private function getContactData($token) {
        $em = $this->getDoctrine()->getManager();
        $repository1 = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository1->findOneBy(array('username' => $token->getUsername()));
        $fullname = $user->getFirstname() . " " . $user->getLastname();
        $currentCompany = $user->getCompanyname();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
        $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

        $contactArray = array();

        foreach ($contacts as $contact) {

            if ($contact->getStatus() == 'Active' || $contact->getUsername() == $user->getUsername()) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('contactid' => $contact->getId(), 'status' => 'Active'));
                $count = 0;
                $woncount = 0;
                $losscount = 0;
                $wonRevenue = 0;
                $lossRevenue = 0;
                $opencount = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                        if ($opportunity->getStage() == 'won') {
                            $woncount++;
                            $wonRevenue += intval(str_replace(',', '', $opportunity->getRevenue()));
                        } else if ($opportunity->getStage() == 'lost') {
                            $losscount++;
                            $lossRevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        } else {
                            $opencount++;
                            //echo $opencount;
                            //exit;
                        }
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());

                //serialize contact obects to array
                $arrElement["name"] = $contact->getName();
                $arrElement["company"] = $contact->getCompany();
                $arrElement["open_deal"] = $opencount;
                $arrElement["projected_revenue"] = $contact->getProjectedrevenue();
                $arrElement["weighted_forecast"] = $contact->getWeightedforecast();
                $arrElement["won_deals"] = number_format($wonRevenue);
                $arrElement["lost_deals"] = number_format($lossRevenue);
                $arrElement["owner"] = $contact->getFirstname() . " " . $contact->getLastname();
                $arrElement["id"] = $contact->getId();
                $arrElement["email"] = $contact->getEmail0();
                $arrElement["telephone"] = $contact->getPhone0();
                $arrElement["tags"] = $contact->getTags();
                $arrElement["username"] = $contact->getUsername();
                $arrElement["status"] = $contact->getStatus();
                array_push($contactArray, $arrElement);
            }
        }
        $response = array('name' => $token->getUsername(), 'role' => $token->getRole(), 'contacts' => $contactArray, 'fullname' => $fullname, 'manageview' => $user->getContactview());
        return json_encode($response);
    }

    public function contactTableDataAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        if ($token) {
            return new Response($this->getContactData($token));
        } else {
            return new Response("error");
        }
    }

    public function contactTableDataUsernameFilterAction(Request $request, $username) {
        $session = $request->getSession();
        $token = $session->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $currentCompany = $user->getCompanyname();
            $repository = $em->getRepository("ContactsContactsBundle:Contacts");
            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany, 'username' => $username));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            $contactArray = array();

            foreach ($contacts as $contact) {
                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());

                //serialize contact obects to array
                $arrElement["name"] = $contact->getName();
                $arrElement["company"] = $contact->getCompany();
                $arrElement["open_deal"] = 0;
                $arrElement["projected_revenue"] = $contact->getProjectedrevenue();
                $arrElement["weighted_forecast"] = $contact->getWeightedforecast();
                $arrElement["won_deals"] = 0;
                $arrElement["lost_deals"] = 0;
                $arrElement["owner"] = $contact->getFirstname() . " " . $contact->getLastname();
                $arrElement["id"] = $contact->getId();
                $arrElement["email"] = $contact->getEmail0();
                $arrElement["telephone"] = $contact->getPhone0();
                $arrElement["tags"] = $contact->getTags();
                $arrElement["username"] = $contact->getUsername();
                array_push($contactArray, $arrElement);
            }
            $response = array('name' => $token->getUsername(), 'role' => $token->getRole(), 'contacts' => $contactArray, 'fullname' => $fullname, 'manageview' => $user->getContactview());
            return new Response(json_encode($response));
            //return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'contacts' => $contacts, 'fullname' => $fullname, 'manageview' => $user->getContactview()));
        } else {
            return new Response("error");
            //return $this->render('LoginLoginBundle:Default:signIn.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function addcontactAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
        $image = $currentUser->getImage();
        if ($image == '' || $image == null) {
            $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
        }

        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $currentCompany = $currentUser->getCompanyname();
        $users = $repository->findBy(array('companyname' => $currentCompany));
        if ($token) {
            return $this->render('ContactsContactsBundle:Default:addContacts.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'users' => $users, 'companyname' => $currentCompany,
                        'fullname' => $fullname, 'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function addcontactV2Action(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");

        $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
        $image = $currentUser->getImage();
        if ($image == '' || $image == null) {
            $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
        }

        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();

        $currentCompany = $currentUser->getCompanyname();

        $users = $repository->findBy(array('companyname' => $currentCompany));

        if ($token) {
            return $this->render('ContactsContactsBundle:Default:addContactsV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'users' => $users, 'companyname' => $currentCompany,
                        'fullname' => $fullname, 'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function createcontactAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $repository1 = $em->getRepository("LoginLoginBundle:Users");
        $users = $repository1->findAll();
        $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
        $image = $currentUser->getImage();
        if ($image == '' || $image == null) {
            $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
        }

        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();


        if ($token) {

            $name = $request->get('name');
            $email = array();
            $email[0] = $request->get('email0');
            $email[1] = $request->get('email1');
            $email[2] = $request->get('email2');
            $email[3] = $request->get('email3');
            $email[4] = $request->get('email4');
            $email[5] = $request->get('email5');
            $email[6] = $request->get('email6');
            $email[7] = $request->get('email7');
            $email[8] = $request->get('email8');
            $email[9] = $request->get('email9');
            $dob = $request->get('dob');
            $companyname = $request->get('companyname');
            $phone = array();
            $phone[0] = $request->get('phone0');
            $phone[1] = $request->get('phone1');
            $phone[2] = $request->get('phone2');
            $phone[3] = $request->get('phone3');
            $phone[4] = $request->get('phone4');
            $phone[5] = $request->get('phone5');
            $phone[6] = $request->get('phone6');
            $phone[7] = $request->get('phone7');
            $phone[8] = $request->get('phone8');
            $phone[9] = $request->get('phone9');
            $address1 = $request->get('address1');
            $address2 = $request->get('address2');
            $city = $request->get('city');
            $state = $request->get('state');
            $postalcode = $request->get('postalcode');
            $tags = $request->get('tags');
            $notes = $request->get('notes');
            $visibility = $request->get('visibility');
            $username = $request->get('username');
            $title = $request->get('title');
            $sub_con_name1 = $request->get('sub_con_name1');
            $sub_con_title1 = $request->get('sub_con_title1');
            $sub_con_email1 = $request->get('sub_con_email1');
            $sub_con_phone1 = $request->get('sub_con_phone1');
            $sub_con_name2 = $request->get('sub_con_name2');
            $sub_con_title2 = $request->get('sub_con_title2');
            $sub_con_email2 = $request->get('sub_con_email2');
            $sub_con_phone2 = $request->get('sub_con_phone2');

            $emailtype = array();
            $emailtype[0] = $request->get('emailtype0');
            $emailtype[1] = $request->get('emailtype1');
            $emailtype[2] = $request->get('emailtype2');
            $emailtype[3] = $request->get('emailtype3');
            $emailtype[4] = $request->get('emailtype4');
            $emailtype[5] = $request->get('emailtype5');
            $emailtype[6] = $request->get('emailtype6');
            $emailtype[7] = $request->get('emailtype7');
            $emailtype[8] = $request->get('emailtype8');
            $emailtype[9] = $request->get('emailtype9');
            $phonetype = array();
            $phonetype[0] = $request->get('phonetype0');
            $phonetype[1] = $request->get('phonetype1');
            $phonetype[2] = $request->get('phonetype2');
            $phonetype[3] = $request->get('phonetype3');
            $phonetype[4] = $request->get('phonetype4');
            $phonetype[5] = $request->get('phonetype5');
            $phonetype[6] = $request->get('phonetype6');
            $phonetype[7] = $request->get('phonetype7');
            $phonetype[8] = $request->get('phonetype8');
            $phonetype[9] = $request->get('phonetype9');

            for ($i = 0; $i < 10; $i++) {
                if ($email[$i] != null && $email[$i] != '') {
                    $email[$i] = $emailtype[$i] . ':' . $email[$i];
                }
            }

            for ($j = 0; $j < 10; $j++) {
                if ($phone[$j] != null && $phone[$j] != '') {
                    $phone[$j] = $phonetype[$j] . ':' . $phone[$j];
                }
            }

            $profileimage = $request->files->get('profileimage');


            if ($profileimage != null && $profileimage->isValid()) {
                $profilestore = $profileimage->move("bundles/contactscontacts/contactimages", split('@', split(':', $email[0])[1])[0] . str_replace(' ', '', split(':', $phone[0])[1]) . "." . split('/', $profileimage->getMimeType())[1]);
            } else {
                $profilestore = '';
            }

            $newcontact = new Contacts();
            $newcontact->setUsername($username);
            $newcontact->setImage($profilestore);
            $newcontact->setName($name);
            $newcontact->setDob($dob);
            $newcontact->setEmail0($email[0]);
            $newcontact->setEmail1($email[1]);
            $newcontact->setEmail2($email[2]);
            $newcontact->setEmail3($email[3]);
            $newcontact->setEmail4($email[4]);
            $newcontact->setEmail5($email[5]);
            $newcontact->setEmail6($email[6]);
            $newcontact->setEmail7($email[7]);
            $newcontact->setEmail8($email[8]);
            $newcontact->setEmail9($email[9]);
            $newcontact->setPhone0($phone[0]);
            $newcontact->setPhone1($phone[1]);
            $newcontact->setPhone2($phone[2]);
            $newcontact->setPhone3($phone[3]);
            $newcontact->setPhone4($phone[4]);
            $newcontact->setPhone5($phone[5]);
            $newcontact->setPhone6($phone[6]);
            $newcontact->setPhone7($phone[7]);
            $newcontact->setPhone8($phone[8]);
            $newcontact->setPhone9($phone[9]);
            $newcontact->setVisibility($visibility);
            $newcontact->setCompany($companyname);
            $newcontact->setAddress1($address1);
            $newcontact->setAddress2($address2);
            $newcontact->setCity($city);
            $newcontact->setState($state);
            $newcontact->setPostalcode($postalcode);
            $newcontact->setTags($tags);
            $newcontact->setContactnotes($notes);
            $newcontact->setStatus('Active');
            $newcontact->setOwnedcompany($currentUser->getCompanyname());
            $newcontact->setTitle($title);
            $newcontact->setSub_con_name1($sub_con_name1);
            $newcontact->setSub_con_title1($sub_con_title1);
            $newcontact->setSub_con_email1($sub_con_email1);
            $newcontact->setSub_con_phone1($sub_con_phone1);
            $newcontact->setSub_con_name2($sub_con_name2);
            $newcontact->setSub_con_title2($sub_con_title2);
            $newcontact->setSub_con_email2($sub_con_email2);
            $newcontact->setSub_con_phone2($sub_con_phone2);
            try {
                $em->persist($newcontact);
                $em->flush();
                $repository1 = $em->getRepository("LoginLoginBundle:Users");
                $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
                $currentCompany = $currentUser->getCompanyname();

                $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
                $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

                foreach ($contacts as $contact) {
                    $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                    $opportunities = $repository2->findBy(array('contactid' => $contact->getId(), 'status' => 'Active'));
                    $count = 0;
                    $woncount = 0;
                    $losscount = 0;
                    $wonRevenue = 0;
                    $lossRevenue = 0;
                    $opencount = 0;
                    $projectedrevenue = 0;
                    $weightedforecast = 0;
                    if ($opportunities) {

                        foreach ($opportunities as $opportunity) {
                            $count++;
                            $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                            $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                            if ($opportunity->getStage() == 'won') {
                                $woncount++;
                                $wonRevenue += intval(str_replace(',', '', $opportunity->getRevenue()));
                            } else if ($opportunity->getStage() == 'lost') {
                                $losscount++;
                                $lossRevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                            } else {
                                $opencount++;
                                //echo $opencount;
                                //exit;
                            }
                        }
                    }
                    $contact->setNoofopportunities($count);
                    $contact->setProjectedrevenue(number_format($projectedrevenue));
                    $contact->setWeightedforecast(number_format($weightedforecast));
                    $contact->setFirstname($currentUser->getFirstname());
                    $contact->setLastname($currentUser->getLastname());
                }
                $response = $this->getContactData($token);

                if ($request->get('opportunityflag') == 'true') {


                    $repository = $em->getRepository("LoginLoginBundle:Users");
                    $repository1 = $em->getRepository("SettingsBundle:Accounttypes");
                    $repository2 = $em->getRepository("SettingsBundle:Stages");
                    $repository3 = $em->getRepository("SettingsBundle:Producttypes");
                    $repository4 = $em->getRepository("SettingsBundle:Opportunitysources");

                    $user = $repository->findOneBy(array('username' => $token->getUsername()));
                    $accounttypes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                    $stages = $repository2->findBy(array('companyname' => $user->getCompanyname()));
                    $producttypes = $repository3->findBy(array('companyname' => $user->getCompanyname()));
                    $opportunitysources = $repository4->findBy(array('companyname' => $user->getCompanyname()));


                    $currentUser = $repository->findOneBy(array('username' => $token->getUsername()));
                    $currentCompany = $currentUser->getCompanyname();
                    $users = $repository->findBy(array('companyname' => $currentCompany));


                    return $this->render('OpportunityBundle:Default:addOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                                'accounttypes' => $accounttypes, 'stages' => $stages,
                                'producttypes' => $producttypes,
                                'opportunitysources' => $opportunitysources,
                                'users' => $users,
                                'successmsg' => 'Well done! You succesfully add that contact.Continue with the add opportunity',
                                'personname' => $name, 'organizationname' => $companyname,
                                'fullname' => $fullname, 'image' => $image));
                }
                $user = $repository1->findOneBy(array('username' => $token->getUsername()));
                return $this->render('ContactsContactsBundle:Default:manageContactV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'successmsg' => 'Well done! You succesfully add a contact',
                            'contacts' => $contacts, 'contactArray' => $response,
                            'fullname' => $fullname, 'manageview' => $user->getContactview(),
                            'image' => $image));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository1 = $em->getRepository("LoginLoginBundle:Users");
                $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
                $currentCompany = $currentUser->getCompanyname();
                $users = $repository1->findBy(array('companyname' => $currentCompany));
                return $this->render('ContactsContactsBundle:Default:addContacts.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                            'users' => $users, 'companyname' => $currentCompany,
                            'errormsg' => 'Oh snap! Something went wrong. Try Again',
                            'fullname' => $fullname, 'image' => $image));
            }
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function contactvalidateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $email = $request->get('email');
        $tel = $request->get('tel');
        $contact = $repository->findOneBy(array('email0' => $email, 'phone0' => $tel));
        if ($contact) {
            return new Response("true");
        } else {
            return new Response("false");
        }
    }

    public function editcontactpageAction(Request $request, $id) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $contact = $repository->findOneBy(array('id' => $id));

        $tele = array();

        $tele[0] = $contact->getPhone0();
        $tele[1] = $contact->getPhone1();
        $tele[2] = $contact->getPhone2();
        $tele[3] = $contact->getPhone3();
        $tele[4] = $contact->getPhone4();
        $tele[5] = $contact->getPhone5();
        $tele[6] = $contact->getPhone6();
        $tele[7] = $contact->getPhone7();
        $tele[8] = $contact->getPhone8();
        $tele[9] = $contact->getPhone9();

        for ($i = 0; $i < 10; $i++) {
            if ($tele[$i] != null) {
                $tele[$i] = split(":", $tele[$i])[1];
                $tele[$i] = str_replace('(', '', $tele[$i]);
                $tele[$i] = str_replace(')', '', $tele[$i]);
                $tele[$i] = str_replace('-', '', $tele[$i]);
                $tele[$i] = str_replace(' ', '', $tele[$i]);
                $tele[$i] = str_replace('Ext.', '', $tele[$i]);
            } else {
                $tele[$i] = '';
            }
        }
        $repository1 = $em->getRepository("LoginLoginBundle:Users");
        $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $currentCompany = $currentUser->getCompanyname();
        $users = $repository1->findBy(array('companyname' => $currentCompany));

        if ($token && strtolower($contact->getUsername()) == strtolower($token->getUsername())) {
            return $this->render('ContactsContactsBundle:Default:editContacts.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'contact' => $contact, 'tele' => $tele, 'users' => $users, 'fullname' => $fullname));
        } else {
            $repository2 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository2->findOneBy(array('username' => $token->getUsername()));
            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository3 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository3->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'errormsg' => 'You should be the owner of the contact to do this action', 'contacts' => $contacts, 'fullname' => $fullname, 'manageview' => $user->getContactview()));
        }
    }

    public function editcontactpageV2Action(Request $request, $id) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $contact = $repository->findOneBy(array('id' => $id));

        $tele = array();

        $tele[0] = $contact->getPhone0();
        $tele[1] = $contact->getPhone1();
        $tele[2] = $contact->getPhone2();
        $tele[3] = $contact->getPhone3();
        $tele[4] = $contact->getPhone4();
        $tele[5] = $contact->getPhone5();
        $tele[6] = $contact->getPhone6();
        $tele[7] = $contact->getPhone7();
        $tele[8] = $contact->getPhone8();
        $tele[9] = $contact->getPhone9();

        for ($i = 0; $i < 10; $i++) {
            if ($tele[$i] != null) {
                $tele[$i] = split(":", $tele[$i])[1];
                $tele[$i] = str_replace('(', '', $tele[$i]);
                $tele[$i] = str_replace(')', '', $tele[$i]);
                $tele[$i] = str_replace('-', '', $tele[$i]);
                $tele[$i] = str_replace(' ', '', $tele[$i]);
                $tele[$i] = str_replace('Ext.', '', $tele[$i]);
            } else {
                $tele[$i] = '';
            }
        }
        $repository1 = $em->getRepository("LoginLoginBundle:Users");
        $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
        $image = $currentUser->getImage();
        if ($image == '' || $image == null) {
            $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
        }
        
        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $currentCompany = $currentUser->getCompanyname();
        $users = $repository1->findBy(array('companyname' => $currentCompany));

        if ($token && strtolower($contact->getUsername()) == strtolower($token->getUsername())) {
            return $this->render('ContactsContactsBundle:Default:editContactsV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'contact' => $contact, 'tele' => $tele, 'users' => $users,
                        'fullname' => $fullname, 'image' => $image));
        } else {
            $repository2 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository2->findOneBy(array('username' => $token->getUsername()));
            $image = $currentUser->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository3 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository3->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'errormsg' => 'You should be the owner of the contact to do this action',
                        'contacts' => $contacts, 'fullname' => $fullname,
                        'manageview' => $user->getContactview(), 'image' => $image));
        }
    }

    public function updatecontactAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        if ($token && $request->getMethod() == "POST") {
            $id = $request->get('id');
            $email = array();
            $email[0] = $request->get('email0');
            $email[1] = $request->get('email1');
            $email[2] = $request->get('email2');
            $email[3] = $request->get('email3');
            $email[4] = $request->get('email4');
            $email[5] = $request->get('email5');
            $email[6] = $request->get('email6');
            $email[7] = $request->get('email7');
            $email[8] = $request->get('email8');
            $email[9] = $request->get('email9');

            $phone = array();
            $phone[0] = $request->get('phone0');
            $phone[1] = $request->get('phone1');
            $phone[2] = $request->get('phone2');
            $phone[3] = $request->get('phone3');
            $phone[4] = $request->get('phone4');
            $phone[5] = $request->get('phone5');
            $phone[6] = $request->get('phone6');
            $phone[7] = $request->get('phone7');
            $phone[8] = $request->get('phone8');
            $phone[9] = $request->get('phone9');

            $emailtype = array();
            $emailtype[0] = $request->get('emailtype0');
            $emailtype[1] = $request->get('emailtype1');
            $emailtype[2] = $request->get('emailtype2');
            $emailtype[3] = $request->get('emailtype3');
            $emailtype[4] = $request->get('emailtype4');
            $emailtype[5] = $request->get('emailtype5');
            $emailtype[6] = $request->get('emailtype6');
            $emailtype[7] = $request->get('emailtype7');
            $emailtype[8] = $request->get('emailtype8');
            $emailtype[9] = $request->get('emailtype9');
            $phonetype = array();
            $phonetype[0] = $request->get('phonetype0');
            $phonetype[1] = $request->get('phonetype1');
            $phonetype[2] = $request->get('phonetype2');
            $phonetype[3] = $request->get('phonetype3');
            $phonetype[4] = $request->get('phonetype4');
            $phonetype[5] = $request->get('phonetype5');
            $phonetype[6] = $request->get('phonetype6');
            $phonetype[7] = $request->get('phonetype7');
            $phonetype[8] = $request->get('phonetype8');
            $phonetype[9] = $request->get('phonetype9');

            for ($i = 0; $i < 10; $i++) {
                if ($email[$i] != null && $email[$i] != '') {
                    $email[$i] = $emailtype[$i] . ':' . $email[$i];
                }
            }

            for ($j = 0; $j < 10; $j++) {
                if ($phone[$j] != null && $phone[$j] != '') {
                    $phone[$j] = $phonetype[$j] . ':' . $phone[$j];
                }
            }

            $contact = $repository->findOneBy(array('id' => $id));
            $contact->setName($request->get('name'));
            $contact->setCompany($request->get('companyname'));
            $contact->setEmail0($email[0]);
            $contact->setEmail1($email[1]);
            $contact->setEmail2($email[2]);
            $contact->setEmail3($email[3]);
            $contact->setEmail4($email[4]);
            $contact->setEmail5($email[5]);
            $contact->setEmail6($email[6]);
            $contact->setEmail7($email[7]);
            $contact->setEmail8($email[8]);
            $contact->setEmail9($email[9]);
            $contact->setPhone0($phone[0]);
            $contact->setPhone1($phone[1]);
            $contact->setPhone2($phone[2]);
            $contact->setPhone3($phone[3]);
            $contact->setPhone4($phone[4]);
            $contact->setPhone5($phone[5]);
            $contact->setPhone6($phone[6]);
            $contact->setPhone7($phone[7]);
            $contact->setPhone8($phone[8]);
            $contact->setPhone9($phone[9]);
            $contact->setDob($request->get('dob'));
            $contact->setAddress1($request->get('address1'));
            $contact->setAddress2($request->get('address2'));
            $contact->setCity($request->get('city'));
            $contact->setState($request->get('state'));
            $contact->setPostalcode($request->get('postalcode'));
            $contact->setTags($request->get('tags'));
            $contact->setContactnotes($request->get('notes'));
            $contact->setTitle($request->get('title'));
            $contact->setSub_con_name1($request->get('sub_con_name1'));
            $contact->setSub_con_title1($request->get('sub_con_title1'));
            $contact->setSub_con_email1($request->get('sub_con_email1'));
            $contact->setSub_con_phone1($request->get('sub_con_phone1'));
            $contact->setSub_con_name2($request->get('sub_con_name2'));
            $contact->setSub_con_title2($request->get('sub_con_title2'));
            $contact->setSub_con_email2($request->get('sub_con_email2'));
            $contact->setSub_con_phone2($request->get('sub_con_phone2'));
            $profileimage = $request->files->get('profileimage');


            if ($profileimage != null && $profileimage->isValid()) {
                $profilestore = $profileimage->move("bundles/contactscontacts/contactimages", split('@', split(':', $email[0])[1])[0] . str_replace(' ', '', split(':', $phone[0])[1]) . "." . split('/', $profileimage->getMimeType())[1]);
                $contact->setImage($profilestore);
            }

            try {
                $em->flush();
                $repository1 = $em->getRepository("LoginLoginBundle:Users");
                $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
                $image = $currentUser->getImage();
                if ($image == '' || $image == null) {
                    $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
                }
                
                $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
                $currentCompany = $currentUser->getCompanyname();

                $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
                $repository2 = $em->getRepository("OpportunityBundle:Opportunities");
                
                foreach ($contacts as $contact) {

                    $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                    $opportunities = $repository2->findBy(array('contactid' => $contact->getId(), 'status' => 'Active'));
                    $count = 0;
                    $woncount = 0;
                    $losscount = 0;
                    $wonRevenue = 0;
                    $lossRevenue = 0;
                    $opencount = 0;
                    $projectedrevenue = 0;
                    $weightedforecast = 0;
                    if ($opportunities) {

                        foreach ($opportunities as $opportunity) {
                            $count++;
                            $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                            $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                            if ($opportunity->getStage() == 'won') {
                                $woncount++;
                                $wonRevenue += intval(str_replace(',', '', $opportunity->getRevenue()));
                            } else if ($opportunity->getStage() == 'lost') {
                                $losscount++;
                                $lossRevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                            } else {
                                $opencount++;
                                //echo $opencount;
                                //exit;
                            }
                        }
                    }
                    $contact->setNoofopportunities($count);
                    $contact->setProjectedrevenue(number_format($projectedrevenue));
                    $contact->setWeightedforecast(number_format($weightedforecast));
                    $contact->setFirstname($currentUser->getFirstname());
                    $contact->setLastname($currentUser->getLastname());

                    
                }
                $response = $this->getContactData($token);

                $user = $repository1->findOneBy(array('username' => $token->getUsername()));
                return $this->render('ContactsContactsBundle:Default:manageContactV2.html.twig', 
                        array('name' => $token->getUsername(), 'role' => $token->getRole(), 
                            'fullname' => $fullname, 'contactArray' => $response, 
                            'successmsg' => 'Well done ! you succesfully update your contact', 
                            'contacts' => $contacts, 'manageview' => $user->getContactview(),
                            'image' => $image));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $repository1 = $em->getRepository("LoginLoginBundle:Users");
                $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
                $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
                $currentCompany = $currentUser->getCompanyname();

                $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
                $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

                foreach ($contacts as $contact) {

                    $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                    $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                    $count = 0;
                    $projectedrevenue = 0;
                    $weightedforecast = 0;
                    if ($opportunities) {

                        foreach ($opportunities as $opportunity) {
                            $count++;
                            $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                            $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                        }
                    }
                    $contact->setNoofopportunities($count);
                    $contact->setProjectedrevenue(number_format($projectedrevenue));
                    $contact->setWeightedforecast(number_format($weightedforecast));
                    $contact->setFirstname($currentUser->getFirstname());
                    $contact->setLastname($currentUser->getLastname());
                }
                $user = $repository1->findOneBy(array('username' => $token->getUsername()));
                return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Oh snap ! Something went wrong.Try Again.', 'contacts' => $contacts, 'manageview' => $user->getContactview()));
            }
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function activateAction(Request $request, $id) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $contact = $repository->findOneBy(array('id' => $id));
        if ($token && ($token->getUsername() == $contact->getUsername())) {
            $contact->setStatus('Active');
            $em->flush();
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'successmsg' => 'Well done ! you succesfully activate your contact', 'contacts' => $contacts, 'manageview' => $user->getContactview()));
        } else {
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Oh snap ! You should be the owner of the contact to do this action', 'contacts' => $contacts, 'manageview' => $user->getContactview()));
        }
    }

    public function deletecontactAction(Request $request, $id) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $contact = $repository->findOneBy(array('id' => $id));
        if ($token && ($token->getUsername() == $contact->getUsername())) {
            $contact->setStatus('Inactive');
            $em->flush();
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'successmsg' => "Well done! You have successfully deleted a contact. If you've accidently deleted contacts you can restore them. To learn on how to restore a Contact go to the Support section", 'contacts' => $contacts, 'manageview' => $user->getContactview()));
        } else {
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            $currentCompany = $currentUser->getCompanyname();

            $contacts = $repository->findBy(array('ownedcompany' => $currentCompany));
            $repository2 = $em->getRepository("OpportunityBundle:Opportunities");

            foreach ($contacts as $contact) {

                $currentUser = $repository1->findOneBy(array('username' => $contact->getUsername()));
                $opportunities = $repository2->findBy(array('personname' => $contact->getName(), 'status' => 'Active'));
                $count = 0;
                $projectedrevenue = 0;
                $weightedforecast = 0;
                if ($opportunities) {

                    foreach ($opportunities as $opportunity) {
                        $count++;
                        $projectedrevenue += intval(str_replace(',', '', $opportunity->getProjectedrevenue()));
                        $weightedforecast += intval(str_replace(',', '', $opportunity->getForecast()));
                    }
                }
                $contact->setNoofopportunities($count);
                $contact->setProjectedrevenue(number_format($projectedrevenue));
                $contact->setWeightedforecast(number_format($weightedforecast));
                $contact->setFirstname($currentUser->getFirstname());
                $contact->setLastname($currentUser->getLastname());
            }
            $user = $repository1->findOneBy(array('username' => $token->getUsername()));
            return $this->render('ContactsContactsBundle:Default:manageContact.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Oh snap ! You should be the owner of the contact to do this action', 'contacts' => $contacts, 'manageview' => $user->getContactview()));
        }
    }

    public function uploadcontactAction(Request $request) {

        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");
        $repository1 = $em->getRepository("LoginLoginBundle:Users");
        $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
        $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $currentCompany = $currentUser->getCompanyname();

        $file = $request->files->get('file');
        $fileOpen = fopen($file, "r");
        $data = array();


        $flag = true;
        $errorlist = array();
        $count = 0;
        while (($data = fgetcsv($fileOpen, 1000, ",")) !== FALSE) {
            if ($flag) {

                $flag = false;
                continue;
            }
            // need to do after the decision
            $contact = new Contacts();
            $contact->setName($data[0]);
            $contact->setCompany($data[1]);
            $contact->setEmail0("Primary:" . $data[2]);
            $contact->setPhone0("Work-Primary:" . $data[3]);
            $contact->setVisibility("public");
            $contact->setUsername($token->getUsername());
            $contact->setStatus('Active');
            $contact->setOwnedcompany($currentCompany);

            $exist = $repository->findOneBy(array('email0' => "Primary:" . $data[2], 'phone0' => "Work-Primary:" . $data[3]));
            if ($exist) {
                $exposedContact = new ExposedContact();
                $exposedContact->email = $data[2];
                $exposedContact->officetel = $data[3];
                $errorlist[$count] = $exposedContact;
                $count++;
                continue;
            }

            try {
                $em->persist($contact);

                $em->flush();
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('ContactsContactsBundle:Default:importContacts.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Oh snap! Invalid Arguments,Try Again'));
            }
        }
        if (empty($errorlist)) {
            return new Response('Success');
        } else {
            return new Response(json_encode($errorlist));
            // return $this->render('ContactsContactsBundle:Default:importContact.html.twig', array('name'=>$token->getUsername(),'role' => $token->getRole(),'errormsg' => 'Some duplicate contacts found, Unique contacts imported sucessfully ','errorlist'=>$errorlist));
        }
    }

    public function importcontactsAction(Request $request) {
        $session = $request->getSession();
        $token = $session->get('token');
        $em = $this->getDoctrine()->getManager();
        if ($token) {
            $repository1 = $em->getRepository("LoginLoginBundle:Users");
            $currentUser = $repository1->findOneBy(array('username' => $token->getUsername()));
            $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();
            return $this->render('ContactsContactsBundle:Default:importContactsV2.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function contactavailabilityAction(Request $request) {
        $personname = $request->get('personname');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("ContactsContactsBundle:Contacts");

        $user = $repository->findOneBy(array('name' => $personname));

        if ($user) {
            return new Response($user->getCompany());
        } else {
            return new Response("false");
        }
    }

    public function filteredopportunitiesAction(Request $request, $name) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
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
            $opportunities = $repository1->findBy(array('personname' => $name, 'status' => 'Active'));
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
                            $sharedPercentage += $sharingUser->getCommissionnonoriginator();
                        }

                        if ($opportunity->getUsername() == $currentuser->getUsername()) {
                            if (($sharedPercentage + $currentuser->getCommissionoriginator()) >= 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $currentuser->getCommissionoriginator()) / 100;
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
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($currentuser->getCommissionoriginator() + (100 - ($sharedPercentage + $currentuser->getCommissionoriginator())))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                                $isselected = true;
                            }
                        } else if (in_array($currentuser->getUsername(), $sharedusers)) {
                            if (($sharedPercentage + $ownedUser->getCommissionoriginator()) > 100) {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * ($currentuser->getCommissionnonoriginator() - ((($sharedPercentage + $ownedUser->getCommissionoriginator()) - 100 ) / count($sharedusers)))) / 100;
                                $decimalCount = explode(".", $individualforecast);
                                if (count($decimalCount) > 1) {
                                    $individualforecast = number_format(floor($individualforecast), 0) . '.' . $decimalCount[1];
                                } else {
                                    $individualforecast = number_format($individualforecast, 0);
                                }
                                $isselected = true;
                            } else {
                                $individualforecast = (str_replace(",", "", $opportunity->getForecast()) * $currentuser->getCommissionnonoriginator()) / 100;
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

                        $isselected = true;
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

}
