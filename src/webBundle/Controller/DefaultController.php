<?php

namespace webBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use SettingsBundle\Entity\Accounttypes;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('webBundle:Default:index.html.twig', array('name' => $name));
    }

    public function supportAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $image = $user->getImage();
            if ($image == '' || $image == null) {
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }

            $fullname = $user->getFirstname() . " " . $user->getLastname();

            return $this->render('webBundle:Default:support.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'fullname' => $fullname, 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function dashboardAction(Request $request) {

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

            $repository1 = $em->getRepository("ContactsContactsBundle:Contacts");
            $contacts = $repository1->findBy(array('username' => $token->getUsername()));
            $contactsCount = sizeof($contacts);

            $deals = $this->countDeals($token);
            $opendealCount = 0;
            for ($i = 0; $i < sizeof($deals); $i++) {
                if ($deals[$i]['stage'] != "won" && $deals[$i]['stage'] != "lost") {
                    $opendealCount += intval($deals[$i]['noOfDeals']);
                }
            }

            $tasksDealCount = $this->getallOpenDealTasksCount($token);
            $tasksContactCount = $this->getContactsTasksCount($token);
            $notesDealCount = $this->getallOpenDealNotesCount($token);
            $notesContactCount = $this->getContactsNotesCount($token);
            $dealSources = $this->countDealSources($token);
            $dealProductTypes = $this->countDealProductTypes($token);
            $wonAndLostDealForBarBarChart = $this->countWonAndLostDealsForBarChart($token);
            $dealsByContactForBarChart = $this->countDealsByContactForBarChart($token);
            $dealsByUserForBarChart = $this->countDealsByUsersForBarChart($token);
            $earningGoalsAndEarningsForAreaChart = $this->getEarningGoalsAndEarnings($token);
            $earnings = $this->getDealsEarnings($token);

            return $this->render('webBundle:Default:dashboard.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'fullname' => $fullname,
                        'deals' => $deals,
                        'dealSources' => $dealSources,
                        'dealProductTypes' => $dealProductTypes,
                        'wonAndLostDealForBarBarChart' => $wonAndLostDealForBarBarChart,
                        'contactsCount' => $contactsCount,
                        'opendealCount' => $opendealCount,
                        'tasksDealCount' => $tasksDealCount,
                        'tasksContactCount' => $tasksContactCount,
                        'dealsByContact' => $dealsByContactForBarChart,
                        'dealsByUser' => $dealsByUserForBarChart,
                        'notesDealCount' => $notesDealCount,
                        'notesContactCount' => $notesContactCount,
                        'earningGoals' => $earningGoalsAndEarningsForAreaChart,
                        'earnings' => $earnings,
                        'image' => $image));
        } else {
            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    private function getEarningGoalsAndEarnings($token) {
        // set the default timezone to use.
        date_default_timezone_set('UTC');

        $em = $this->getDoctrine()->getManager();
        $usersRepository = $em->getRepository("LoginLoginBundle:Users");

        $currentUser = $usersRepository->findOneBy(array('username' => $token->getUsername()));
        $earningGoal = $currentUser->getEarningGoal();

        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");
        $deals = $opportunityRepository->findBy(array('status' => 'Active', 'stage' => 'won'));

        $earning = array();
        foreach ($deals as $deal) {
            //check user authorization
            $flag = false;
            if ($deal->getUsername() !== $currentUser->getUsername()) {

                $sharing = $deal->getSharing();
                $sharedUsers = explode(":", $sharing);

                foreach ($sharedUsers as $user) {
                    if ($user === $currentUser->getUsername()) {
                        $flag = true;
                        break;
                    }
                }
            } else {
                $flag = true;
            }

            if ($flag == true) {
                $closedDateYear = date("Y", strtotime($deal->getCloseddate()));
                if ($deal->getUserRevenue() == "" || $deal->getUserRevenue() == null) {
                    $revenue = 0;
                } else {
                    $revenue = str_replace(",", "", $deal->getUserRevenue());
                }
                if (!array_key_exists($closedDateYear, $earning)) {
                    $earning[$closedDateYear]['year'] = $closedDateYear;
                    $earning[$closedDateYear]['earning'] = $revenue;
                } else {
                    $earning[$closedDateYear]['earning'] += $revenue;
                }
            }
        }

        $dataArray = array();
        $dataArray['goal'] = str_replace(",", "", $earningGoal);
        $dataArray['data'] = $earning;
        //print_r($dataArray);exit;
        return $dataArray;
    }

    private function getallOpenDealTasksCount($token) {

        $em = $this->getDoctrine()->getManager();
        $usersRepository = $em->getRepository("LoginLoginBundle:Users");
        if ($token) {
            $currentUser = $usersRepository->findOneBy(array('username' => $token->getUsername()));

            if ($currentUser) {
                $fullname = $currentUser->getFirstname() . " " . $currentUser->getLastname();

                $tasksRepository = $em->getRepository("TaskBundle:Tasks");

                $tasks = $tasksRepository->findBy(array('tasktype' => 'opportunity', 'status' => 'Active'));

                $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

                $taskArray = array();
                $taskCount = 0;
                foreach ($tasks as $task) {

                    $id = $task->getTaskTypeId();

                    $opportunity = $opportunityRepository->findOneBy(array('id' => $id, 'status' => 'Active'));
                    if ($opportunity) {

                        //check user authorization
                        $flag = false;
                        if ($opportunity->getUsername() !== $currentUser->getUsername()) {

                            $sharing = $opportunity->getSharing();
                            $sharedUsers = explode(":", $sharing);

                            foreach ($sharedUsers as $user) {
                                if ($user === $currentUser->getUsername()) {
                                    $flag = true;
                                    break;
                                }
                            }
                        } else {
                            $flag = true;
                        }

                        if ($flag == true && $opportunity->getStage() !== 'won' && $opportunity->getStage() !== 'lost') {
                            $taskCount ++;
                        }
                    }
                }
                return $taskCount;
            } else {

                return $taskCount;
            }
        } else {
            return $taskCount;
        }
    }

    private function getallOpenDealNotesCount($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $noteRepository = $em->getRepository("NotesBundle:Notes");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $notes = $noteRepository->findBy(array('type' => 'opportunity'));

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $count = 0;
        foreach ($notes as $note) {
            $opportunity = $opportunityRepository->findOneBy(array('id' => $note->getTypeId()));
            if ($opportunity) {

                //check user authorization
                $flag = false;
                if ($opportunity->getUsername() !== $user->getUsername()) {

                    $sharing = $opportunity->getSharing();
                    $sharedUsers = explode(":", $sharing);

                    foreach ($sharedUsers as $username) {
                        if ($username === $user->getUsername()) {
                            $flag = true;
                            break;
                        }
                    }
                } else {
                    $flag = true;
                }

                if ($flag === true) {
                    $count++;
                }
            }
        }

        return $count;
    }

    private function getContactsNotesCount($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $noteRepository = $em->getRepository("NotesBundle:Notes");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");

        $notes = $noteRepository->findBy(array('type' => 'contact'));

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $count = 0;
        foreach ($notes as $note) {
            $contact = $contactRepository->findOneBy(array('id' => $note->getTypeId()));
            if ($contact) {
                if ($contact->getUsername() == $user->getUsername() ||
                        $note->getUsername() == $user->getUsername()) {
                    $count++;
                }
            }
        }
        return $count;
    }

    private function getContactsTasksCount($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");
        $tasksRepository = $em->getRepository("TaskBundle:Tasks");

        $tasks = $tasksRepository->findBy(array('tasktype' => 'contact',
            'status' => 'Active'));

        $currentUser = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $count = 0;

        foreach ($tasks as $task) {

            $id = $task->getTaskTypeId();

            $contact = $contactRepository->findOneBy(array('id' => $id, 'status' => 'Active'));
            if ($contact) {
                if (($contact->getUsername() == $currentUser->getUsername() ||
                        $contact->getUsername() == $currentUser->getUsername())) {
                    $count++;
                }
            }
        }
        return $count;
    }

    private function countDealsByUsersForBarChart($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $deals = $opportunityRepository->findBy(array('status' => 'Active'));

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));
        $currentUserCompany = $user->getCompanyName();

        $usersDummyArray = array();
        $usersArray = array();
        foreach ($deals as $deal) {
            if ($deal->getUsername() == $user->getUsername()) {
                if (!array_key_exists($user->getUsername(), $usersDummyArray)) {
                    $usersDummyArray[$user->getUsername()] = "a";
                    $usersArray[$user->getUsername()]['name'] = $user->getUsername();
                    $usersArray[$user->getUsername()]['won'] = 0;
                    $usersArray[$user->getUsername()]['lost'] = 0;
                    $usersArray[$user->getUsername()]['open'] = 0;

                    if ($deal->getStage() == 'won') {
                        $usersArray[$user->getUsername()]['won'] = 1;
                    } else if ($deal->getStage() == 'lost') {
                        $usersArray[$user->getUsername()]['lost'] = 1;
                    } else {
                        $usersArray[$user->getUsername()]['open'] = 1;
                    }
                } else {
                    if ($deal->getStage() == 'won') {
                        $usersArray[$user->getUsername()]['won'] ++;
                    } else if ($deal->getStage() == 'lost') {
                        $usersArray[$user->getUsername()]['lost'] ++;
                    } else {
                        $usersArray[$user->getUsername()]['open'] ++;
                    }
                }
            } else {
                $usernames = explode(":", $deal->getSharing());
                foreach ($usernames as $tempUsername) {

                    $tempUser = $userRepository->findOneBy(array('username' => $tempUsername, 'companyname' => $currentUserCompany));
                    if ($tempUser) {
                        if (!array_key_exists($tempUser->getUsername(), $usersDummyArray)) {
                            $usersDummyArray[$tempUser->getUsername()] = "a";
                            $usersArray[$tempUser->getUsername()]['name'] = $tempUser->getUsername();
                            $usersArray[$tempUser->getUsername()]['won'] = 0;
                            $usersArray[$tempUser->getUsername()]['lost'] = 0;
                            $usersArray[$tempUser->getUsername()]['open'] = 0;

                            if ($deal->getStage() == 'won') {
                                $usersArray[$tempUser->getUsername()]['won'] = 1;
                            } else if ($deal->getStage() == 'lost') {
                                $usersArray[$tempUser->getUsername()]['lost'] = 1;
                            } else {
                                $usersArray[$tempUser->getUsername()]['open'] = 1;
                            }
                        } else {
                            if ($deal->getStage() == 'won') {
                                $usersArray[$tempUser->getUsername()]['won'] ++;
                            } else if ($deal->getStage() == 'lost') {
                                $usersArray[$tempUser->getUsername()]['lost'] ++;
                            } else {
                                $usersArray[$tempUser->getUsername()]['open'] ++;
                            }
                        }
                    }
                }
            }
        }

        return $usersArray;
    }

    private function countDealsByContactForBarChart($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $deals = $opportunityRepository->findBy(array('status' => 'Active'));

        $contactsArray = array();
        $contactsDummyArray = array();
        foreach ($deals as $deal) {
            if (!array_key_exists($deal->getContactId(), $contactsDummyArray)) {
                $contactsDummyArray[$deal->getContactId()] = "a";
                $contact = $contactRepository->findOneBy(array('id' => $deal->getContactId(), 'username' => $token->getUsername()));
                if ($contact) {
                    $contactsArray[$deal->getContactId()]['name'] = $contact->getName();
                    $contactsArray[$deal->getContactId()]['won'] = 0;
                    $contactsArray[$deal->getContactId()]['lost'] = 0;
                    $contactsArray[$deal->getContactId()]['open'] = 0;

                    if ($deal->getStage() == 'won') {
                        $contactsArray[$deal->getContactId()]['won'] = 1;
                    } else if ($deal->getStage() == 'lost') {
                        $contactsArray[$deal->getContactId()]['lost'] = 1;
                    } else {
                        $contactsArray[$deal->getContactId()]['open'] = 1;
                    }
                }
            } else {
                $contact = $contactRepository->findOneBy(array('id' => $deal->getContactId(), 'username' => $token->getUsername()));
                if ($contact) {
                    $contactsArray[$deal->getContactId()]['name'] = $contact->getName();
                    if ($deal->getStage() == 'won') {
                        $contactsArray[$deal->getContactId()]['won'] ++;
                    } else if ($deal->getStage() == 'lost') {
                        $contactsArray[$deal->getContactId()]['lost'] ++;
                    } else {
                        $contactsArray[$deal->getContactId()]['open'] ++;
                    }
                }
            }
        }

        return $contactsArray;
    }

    private function countDeals($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $stageRepository = $em->getRepository("SettingsBundle:Stages");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $array = array();

        $deals = $opportunityRepository->findBy(array('status' => 'Active'));

        $stagesArray = array();
        foreach ($deals as $deal) {
            if (!array_key_exists($deal->getStage(), $stagesArray)) {
                if ($this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                    $stagesArray[$deal->getStage()] = 1;
                }
            } else {
                if ($this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                    $stagesArray[$deal->getStage()] ++;
                }
            }
        }

        foreach ($stagesArray as $stage => $value) {
            array_push($array, array('stage' => $stage, 'noOfDeals' => $value));
        }

        $returnArray = array();
        foreach ($array as $value) {
            $stage = $stageRepository->findOneBy(array('id' => $value['stage']));
            if ($stage) {
                $value['stage'] = $stage->getName();
                array_push($returnArray, array('stage' => $stage->getName(), 'noOfDeals' => $value['noOfDeals']));
            } else {
                array_push($returnArray, array('stage' => $value['stage'], 'noOfDeals' => $value['noOfDeals']));
            }
        }

        return $returnArray;
    }

    private function getDealsEarnings($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $array = array();

        $deals = $opportunityRepository->findBy(array('status' => 'Active'));

        $earning = 0;
        foreach ($deals as $deal) {
            if ($deal->getStage() != "won" && $deal->getStage() != "lost" && 
                    $this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                $earning += str_replace(",","",$deal->getProjectedRevenue());;
            }
        }

        return number_format($earning);
    }

    private function countDealSources($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");
        $opportunitySourcesRepository = $em->getRepository("SettingsBundle:Opportunitysources");

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $sources = $opportunitySourcesRepository->findBy(array('username' => $user->getUsername(), 'status' => 'Active'));

        $returnArray = array();
        foreach ($sources as $source) {

            $deals = $opportunityRepository->findBy(array('status' => 'Active', 'opportunitysource' => $source->getId()));
            $openDealCount = 0;
            $lostDealCount = 0;
            $wonDealCount = 0;
            foreach ($deals as $deal) {
                if ($this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                    if ($deal->getStage() == 'won') {
                        $wonDealCount++;
                    } else if ($deal->getStage() == 'lost') {
                        $lostDealCount++;
                    } else {
                        $openDealCount++;
                    }
                }
            }
            array_push($returnArray, array('name' => $source->getName(), 'openDeals' => $openDealCount, 'wonDeals' => $wonDealCount,
                'lostDeals' => $lostDealCount));
        }

        return $returnArray;
    }

    private function countDealProductTypes($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");
        $productTypeRepository = $em->getRepository("SettingsBundle:Producttypes");

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $productTypes = $productTypeRepository->findBy(array('username' => $user->getUsername(), 'status' => 'Active'));

        $returnArray = array();
        foreach ($productTypes as $type) {

            $deals = $opportunityRepository->findBy(array('status' => 'Active', 'producttype' => $type->getId()));
            $openDealCount = 0;
            $lostDealCount = 0;
            $wonDealCount = 0;
            foreach ($deals as $deal) {
                if ($this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                    if ($deal->getStage() == 'won') {
                        $wonDealCount++;
                    } else if ($deal->getStage() == 'lost') {
                        $lostDealCount++;
                    } else {
                        $openDealCount++;
                    }
                }
            }
            array_push($returnArray, array('name' => $type->getName(), 'openDeals' => $openDealCount, 'wonDeals' => $wonDealCount,
                'lostDeals' => $lostDealCount));
        }

        return $returnArray;
    }

    private function countWonAndLostDealsForBarChart($token) {
        // set the default timezone to use.
        date_default_timezone_set('UTC');

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $deals = $opportunityRepository->findBy(array('status' => 'Active'));

        $currentYear = date("Y");

        $returnArray = array();
        $returnArray[$currentYear]['year'] = $currentYear;
        $returnArray[$currentYear]['won']['1'] = 0;
        $returnArray[$currentYear]['won']['2'] = 0;
        $returnArray[$currentYear]['won']['3'] = 0;
        $returnArray[$currentYear]['won']['4'] = 0;
        $returnArray[$currentYear]['lost']['1'] = 0;
        $returnArray[$currentYear]['lost']['2'] = 0;
        $returnArray[$currentYear]['lost']['3'] = 0;
        $returnArray[$currentYear]['lost']['4'] = 0;

        $returnArray[$currentYear - 1]['year'] = $currentYear - 1;
        $returnArray[$currentYear - 1]['won']['1'] = 0;
        $returnArray[$currentYear - 1]['won']['2'] = 0;
        $returnArray[$currentYear - 1]['won']['3'] = 0;
        $returnArray[$currentYear - 1]['won']['4'] = 0;
        $returnArray[$currentYear - 1]['lost']['1'] = 0;
        $returnArray[$currentYear - 1]['lost']['2'] = 0;
        $returnArray[$currentYear - 1]['lost']['3'] = 0;
        $returnArray[$currentYear - 1]['lost']['4'] = 0;

        foreach ($deals as $deal) {
            if ($this->isAssociatedDeal($deal, $user->getUsername()) === true) {
                $closedDateMonth = date("m", strtotime($deal->getCloseddate()));
                $closedDateYear = date("Y", strtotime($deal->getCloseddate()));

                if ($deal->getStage() === 'won') {

                    if ($currentYear == $closedDateYear) {
                        if ($closedDateMonth <= 3) {
                            $returnArray[$currentYear]['won']['1'] ++;
                        } elseif ($closedDateMonth <= 6) {
                            $returnArray[$currentYear]['won']['2'] ++;
                        } elseif ($closedDateMonth <= 9) {
                            $returnArray[$currentYear]['won']['3'] ++;
                        } elseif ($closedDateMonth <= 12) {
                            $returnArray[$currentYear]['won']['4'] ++;
                        }
                    }
                    if ($currentYear - 1 == $closedDateYear) {
                        if ($closedDateMonth <= 3) {
                            $returnArray[$currentYear - 1]['won']['1'] ++;
                        } elseif ($closedDateMonth <= 6) {
                            $returnArray[$currentYear - 1]['won']['2'] ++;
                        } elseif ($closedDateMonth <= 9) {
                            $returnArray[$currentYear - 1]['won']['3'] ++;
                        } elseif ($closedDateMonth <= 12) {
                            $returnArray[$currentYear - 1]['won']['4'] ++;
                        }
                    }
                } else if ($deal->getStage() === 'lost') {
                    if ($currentYear == $closedDateYear) {
                        if ($closedDateMonth <= 3) {
                            $returnArray[$currentYear]['lost']['1'] ++;
                        } elseif ($closedDateMonth <= 6) {
                            $returnArray[$currentYear]['lost']['2'] ++;
                        } elseif ($closedDateMonth <= 9) {
                            $returnArray[$currentYear]['lost']['3'] ++;
                        } elseif ($closedDateMonth <= 12) {
                            $returnArray[$currentYear]['lost']['4'] ++;
                        }
                    }
                    if ($currentYear - 1 == $closedDateYear) {
                        if ($closedDateMonth <= 3) {
                            $returnArray[$currentYear - 1]['lost']['1'] ++;
                        } elseif ($closedDateMonth <= 6) {
                            $returnArray[$currentYear - 1]['lost']['2'] ++;
                        } elseif ($closedDateMonth <= 9) {
                            $returnArray[$currentYear - 1]['lost']['3'] ++;
                        } elseif ($closedDateMonth <= 12) {
                            $returnArray[$currentYear - 1]['lost']['4'] ++;
                        }
                    }
                }
            }
        }

        return $returnArray;
    }

    private function isAssociatedDeal($deal, $username) {
        if ($deal->getUsername() === $username) {
            return true;
        } else {
            $usernames = explode(":", $deal->getSharing());
            foreach ($usernames as $tempUsername) {
                if ($tempUsername === $username) {
                    return true;
                }
            }
        }
        return false;
    }

}
