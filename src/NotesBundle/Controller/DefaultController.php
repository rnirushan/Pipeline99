<?php

namespace NotesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NotesBundle\Entity\Notes;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('NotesBundle:Default:index.html.twig', array('name' => $name));
    }

    public function savenotesAction(Request $request) {

        $token = $request->getSession()->get('token');
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");


        if ($token && $request->getMethod() == 'POST') {
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $name = $request->get('notename');
            $tags = $request->get('tags');
            $note = $request->get('notes');
            $visibility = $request->get('visibility');
            $due = $request->get('due');
            $priority = $request->get('priority');
            $type = $request->get('type');
            $typeid = $request->get('typeid');
            $userfullname = $user->getFirstname() . " " . $user->getLastname();

            $notes = new Notes();
            $notes->setName($name);
            $notes->setUsername($token->getUsername());
            $notes->setFullname($userfullname);
            $notes->setCompanyname($user->getCompanyname());
            $notes->setStatus('Active');
            $notes->setTags($tags);
            $notes->setNotes($note);
            $notes->setVisibility($visibility);
            $notes->setDue($due);
            $notes->setPriority($priority);
            $notes->setType($type);
            $notes->setTypeid($typeid);
            date_default_timezone_set('America/Los_Angeles');
            $notes->setTimestamp(date("m-d-Y h:i:sa"));
            try {
                $em->persist($notes);
                $em->flush();

                $repository1 = $em->getRepository("OpportunityBundle:Opportunities");
                $repository2 = $em->getRepository("SettingsBundle:Stages");

                $currentuser = $repository->findOneBy(array('username' => $token->getUsername()));
                $opportunities = $repository1->findBy(array('ownedcompany' => $currentuser->getCompanyname()));
                foreach ($opportunities as $opportunity) {
                    $stage = $repository2->findOneBy(array('name' => $opportunity->getStage()));
                    if ($stage) {
                        $opportunity->setChanceofsale($stage->getSalechance());
                    }
                    if ($opportunity->getStage() == 'Won') {
                        $opportunity->setChanceofsale('100');
                    }
                    if ($opportunity->getStage() == 'Lost') {
                        $opportunity->setChanceofsale('0');
                    }
                }


                return $this->render('OpportunityBundle:Default:manageOpportunity.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'opportunities' => $opportunities, 'fullname' => $fullname, 'successmsg' => 'You successfully add a note to the opportunity'));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {

                return $this->render('SettingsBundle:Default:addStage.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'fullname' => $fullname, 'errormsg' => 'Invalid Arguments. Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signIn.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function managenotesAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $repository1 = $em->getRepository("NotesBundle:Notes");
            $notes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
            return $this->render('NotesBundle:Default:manageNotes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'notes' => $notes, 'fullname' => $fullname));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function managenotesV2Action(Request $request, $type) {
        $token = $request->getSession()->get('token');
        if ($token) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $image = $user->getImage();
            if($image == '' || $image == null){
                $image = 'bundles_v2.0/img/Flobbies75x75/Popie.png';
            }
            
            $fullname = $user->getFirstname() . " " . $user->getLastname();
            $noteRepository = $em->getRepository("NotesBundle:Notes");
            $notes = $noteRepository->findBy(array('companyname' => $user->getCompanyname()));
            
            if ($type === 'opportunity'){
                $notesArray = $this->getNotesData($token); 
            } else {
                $notesArray = $this->getContactsNotesData($token);
            }
            return $this->render('NotesBundle:Default:manageNotesV2.html.twig', 
                    array('name' => $token->getUsername(), 'role' => $token->getRole(),
                        'notes' => $notes, 'fullname' => $fullname, 'manageview' => '10', 
                        'notesArray' => $notesArray, 'image' => $image));
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'Please Login your account before you proceed.'));
        }
    }

    public function getNoteTableDataAction(Request $request){
        $token = $request->getSession()->get('token');
        if ($token) {
            $notesArray = $this->getNotesData($token);
            
            return new Response($notesArray);
        }else{
            return false;
        }
    }

    private function getContactsNotesData($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $noteRepository = $em->getRepository("NotesBundle:Notes");
        $contactRepository = $em->getRepository("ContactsContactsBundle:Contacts");

        $notes = $noteRepository->findBy(array('type' => 'contact'));
        
        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $notesArray = array();
        foreach ($notes as $note) {
            $contact = $contactRepository->findOneBy(array('id' => $note->getTypeId()));
            if ($contact) {
                
                if ($contact->getUsername() == $user->getUsername() || 
                        $note->getUsername() == $user->getUsername()) {
                    $arrElement["content"] = $note->getNotes();
                    $arrElement["dateAndTime"] = $note->getTimestamp();
                    $arrElement["owner"] = $note->getUsername();
                    $arrElement["tags"] = $note->getTags();
                    array_push($notesArray, $arrElement);
                }
            }
        }
        $response = array('notes' => $notesArray);
        return json_encode($response);
    }
    
    private function getNotesData($token) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository("LoginLoginBundle:Users");
        $noteRepository = $em->getRepository("NotesBundle:Notes");
        $opportunityRepository = $em->getRepository("OpportunityBundle:Opportunities");

        $notes = $noteRepository->findBy(array('type' => 'opportunity'));
        
        $user = $userRepository->findOneBy(array('username' => $token->getUsername()));

        $notesArray = array();
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
                
                if($flag === true){
                    $arrElement["content"] = $note->getNotes();
                    $arrElement["dateAndTime"] = $note->getTimestamp();
                    $arrElement["owner"] = $note->getUsername();
                    $arrElement["tags"] = $note->getTags();
                    array_push($notesArray, $arrElement);
                }
            }
        }
        $response = array('notes' => $notesArray);
        return json_encode($response);
    }

    public function updatenoteAction(Request $request) {
        $token = $request->getSession()->get('token');
        if ($token && $request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            $repository1 = $em->getRepository("NotesBundle:Notes");
            $id = $request->get('noteid');
            $note = $repository1->findOneBy(array('id' => $id));
            $note->setName($request->get('notename'));
            $note->setTags($request->get('tags'));
            $note->setNotes($request->get('notes'));
            $note->setVisibility($request->get('visibility'));
            $note->setPriority($request->get('priority'));

            $repository = $em->getRepository("LoginLoginBundle:Users");
            $user = $repository->findOneBy(array('username' => $token->getUsername()));
            $fullname = $user->getFirstname() . " " . $user->getLastname();

            try {
                $em->flush();

                $notes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                return $this->render('NotesBundle:Default:manageNotes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'notes' => $notes, 'fullname' => $fullname, 'successmsg' => "Well done ! You successfully update the note"));
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {


                $notes = $repository1->findBy(array('companyname' => $user->getCompanyname()));
                return $this->render('NotesBundle:Default:manageNotes.html.twig', array('name' => $token->getUsername(), 'role' => $token->getRole(), 'notes' => $notes, 'fullname' => $fullname, 'errormsg' => 'Something went wrong. Try Again'));
            }
        } else {

            return $this->render('LoginLoginBundle:Default:signinV2.html.twig', array('errormsg' => 'You need admin login to proceed.'));
        }
    }

    public function getnoteAction(Request $request) {
        $id = $request->get('id');


        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("NotesBundle:Notes");

        $note = $repository->findOneBy(array('id' => $id));

        if ($note) {
            return new Response($note->getPriority() . '*' . $note->getVisibility() . '*' . $note->getName() . '*' . $note->getNotes() . '*' . $note->getTags());
        } else {
            return new Response("false");
        }
    }

    public function getnotesAction(Request $request) {
        $id = $request->get('id');
        $type = $request->get('type');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("NotesBundle:Notes");
        $repository1 = $em->getRepository("LoginLoginBundle:Users");


        $notes = $repository->findBy(array('type' => $type, 'typeid' => $id));

        $notesArray = array();

        if ($notes) {
            foreach ($notes as $note) {
                $user = $repository1->findOneBy(array('username' => $note->getUsername()));
                //$toUser =  $repository1 ->findOneBy(array('id' => $id));
                $toUser = $user;
                $notesString["id"] = $note->getId();
                $notesString["timestamp"] = $note->getTimestamp();
                $notesString["fromUsername"] = $note->getUsername();
                $notesString["toUsername"] = $toUser->getUsername();
                $notesString["fullname"] = $note->getFullname();
                $notesString["notes"] = $note->getNotes();
                $notesString["url"] = $user->getImage();

                array_push($notesArray, $notesString);
            }
            $response = array('notes' => $notesArray);
            $response = json_encode($response);
            return new Response($response);
        } else {
            return new Response("false");
        }
    }

    public function savenoteAction(Request $request) {

        $type = $request->get('type');
        $type_id = $request->get('type_id');
        $note = $request->get('note');
        $username = $request->get('username');
        //echo $username ."***";
        //exit;
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LoginLoginBundle:Users");
        $user = $repository->findOneBy(array('username' => $username));
        // $toUser = $repository->findOneBy(array('id' => $type_id));
        $toUser = $user;
        $notes = new Notes();
        $notes->setType($type);
        $notes->setTypeid($type_id);
        $notes->setUsername($username);
        $notes->setNotes($note);
        $notes->setCompanyname($user->getCompanyname());
        $notes->setStatus('Active');
        $notes->setFullname($user->getFirstname() . " " . $user->getLastname());
        date_default_timezone_set('America/Los_Angeles');
        $notes->setTimestamp(date("m/d/Y h:i:sa"));

        $em->persist($notes);
        $em->flush();

        $response = array('id' => $notes->getId(), 'timestamp' => $notes->getTimestamp(), 'fullname' => $notes->getFullname(), 'notes' => $notes->getNotes(), 'url' => $user->getImage(), 'toUsername' => $toUser->getUsername());
        $response = json_encode($response);

        if ($response) {
            return new Response($response);
        } else {
            return new Response("false");
        }
    }

    public function updatenotesAction(Request $request) {
        $id = $request->get('noteid');
        $note = $request->get('note');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("NotesBundle:Notes");

        $notes = $repository->findOneBy(array('id' => $id));
        $notes->setNotes($note);

        $em->flush();
        $updated_notes = $repository->findOneBy(array('id' => $id));

        if ($updated_notes) {
            $response = array('id' => $updated_notes->getId(), 'timestamp' => $updated_notes->getTimestamp(), 'fullname' => $updated_notes->getFullname(), 'notes' => $updated_notes->getNotes());
            $response = json_encode($response);

            return new Response($response);
        } else {
            return new Response("false");
        }
    }

}
