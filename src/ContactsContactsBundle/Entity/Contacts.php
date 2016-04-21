<?php

namespace ContactsContactsBundle\Entity;


class Contacts {
    
    private $id;
    
    private $username;
    
    private $name;
    
    private $email0;
    
    private $email1;
    
    private $email2;
    
    private $email3;
    
    private $email4;
    
    private $email5;
    
    private $email6;
    
    private $email7;
    
    private $email8;
    
    private $email9;
    
    private $phone0;
    
    private $phone1;
    
    private $phone2;
     
    private $phone3;
      
    private $phone4;
       
    private $phone5;
        
    private $phone6;
         
    private $phone7;
          
    private $phone8;
           
    private $phone9;
    
    private $dob;
    
    private $company;
    
    private $address1;
    
    private $address2;
    
    private $city;
    
    private $state;
    
    private $postalcode;
    
    private $image;
    
    private $tags;
    
    private $contactnotes;
    
    private $status;
    
    private $visibility;
    
    private $ownedcompany;
    
    private $firstname;
    
    private $lastname;
    
    private $noofopportunities;
    
    private $projectedrevenue;
    
    private $weightedforecast;
    
    private $title;
    
    private $sub_con_name1;
    
    private $sub_con_title1;
    
    private $sub_con_email1;
    
    private $sub_con_phone1;
    
    private $sub_con_name2;
    
    private $sub_con_title2;
    
    private $sub_con_email2;
    
    private $sub_con_phone2;
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getSub_con_name1() {
        return $this->sub_con_name1;
    }
    
    public function getSub_con_title1() {
        return $this->sub_con_title1;
    }
    
    public function getSub_con_email1() {
        return $this->sub_con_email1;
    }
    
    public function getSub_con_phone1() {
        return $this->sub_con_phone1;
    }
    
    public function getSub_con_name2() {
        return $this->sub_con_name2;
    }
    
    public function getSub_con_title2() {
        return $this->sub_con_title2;
    }
    
    public function getSub_con_email2() {
        return $this->sub_con_email2;
    }
    
    public function getSub_con_phone2() {
        return $this->sub_con_phone2;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }
    
    public function setSub_con_name1($sub_con_name1){
        $this->sub_con_name1 = $sub_con_name1;
        return $this;
    }
    
    public function setSub_con_title1($sub_con_title1){
        $this->sub_con_title1 = $sub_con_title1;
        return $this;
    }
    
    public function setSub_con_email1($sub_con_email1){
        $this->sub_con_email1 = $sub_con_email1;
        return $this;
    }
    
    public function setSub_con_phone1($sub_con_phone1){
        $this->sub_con_phone1 = $sub_con_phone1;
        return $this;
    }
    
    public function setSub_con_name2($sub_con_name2){
        $this->sub_con_name2 = $sub_con_name2;
        return $this;
    }
    
    public function setSub_con_title2($sub_con_title2){
        $this->sub_con_title2 = $sub_con_title2;
        return $this;
    }
    
    public function setSub_con_email2($sub_con_email2){
        $this->sub_con_email2 = $sub_con_email2;
        return $this;
    }
    
    public function setSub_con_phone2($sub_con_phone2){
        $this->sub_con_phone2 = $sub_con_phone2;
        return $this;
    }

    public function getNoofopportunities(){
        return $this->noofopportunities;
    }
    
    public function getProjectedrevenue(){
        return $this->projectedrevenue;
    }
    
    public function getWeightedforecast(){
        return $this->weightedforecast;
    }
    
    public function setNoofopportunities($noofopportunities){
        $this->noofopportunities = $noofopportunities;
        return $this;
    }
    
    public function setProjectedrevenue($projectedrevenue){
        $this->projectedrevenue = $projectedrevenue;
        return $this;
    }
    
    public function setWeightedforecast($weightedforecast){
        $this->weightedforecast = $weightedforecast;
        return $this;
    }
    
    
    public function getFirstname(){
        return $this->firstname;
    }
    
    public function getLastname(){
        return $this->lastname;
    }
    
    public function setFirstname($firstname){
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname){
        $this->lastname = $lastname;
        return $this;
    }
    public function getOwnedcompany(){
        return $this->ownedcompany;
    }
    
    public function setOwnedcompany($ownedcompany) {
        $this->ownedcompany = $ownedcompany;
        return $this;
    }


    public function getId()
    {
        return $this->id;
    }

    
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail0($email)
    {
        $this->email0 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail0()
    {
        return $this->email0;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail1($email)
    {
        $this->email1 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail1()
    {
        return $this->email1;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail2($email)
    {
        $this->email2 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail2()
    {
        return $this->email2;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail3($email)
    {
        $this->email3 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail3()
    {
        return $this->email3;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail4($email)
    {
        $this->email4 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail4()
    {
        return $this->email4;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail5($email)
    {
        $this->email5 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail5()
    {
        return $this->email5;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail6($email)
    {
        $this->email6 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail6()
    {
        return $this->email6;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail7($email)
    {
        $this->email7 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail7()
    {
        return $this->email7;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail8($email)
    {
        $this->email8 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail8()
    {
        return $this->email8;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Contacts
     */
    public function setEmail9($email)
    {
        $this->email9 = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail9()
    {
        return $this->email9;
    }

   
    public function setPhone0($phone)
    {
        $this->phone0 = $phone;

        return $this;
    }

   
    public function getPhone0()
    {
        return $this->phone0;
    }
    
    public function setPhone1($phone)
    {
        $this->phone1 = $phone;

        return $this;
    }

   
    public function getPhone1()
    {
        return $this->phone1;
    }
    
    public function setPhone2($phone)
    {
        $this->phone2 = $phone;

        return $this;
    }

   
    public function getPhone2()
    {
        return $this->phone2;
    }
    
    public function setPhone3($phone)
    {
        $this->phone3 = $phone;

        return $this;
    }

   
    public function getPhone3()
    {
        return $this->phone3;
    }
    
    public function setPhone4($phone)
    {
        $this->phone4 = $phone;

        return $this;
    }

   
    public function getPhone4()
    {
        return $this->phone4;
    }
    
    public function setPhone5($phone)
    {
        $this->phone5 = $phone;

        return $this;
    }

   
    public function getPhone5()
    {
        return $this->phone5;
    }
    
    public function setPhone6($phone)
    {
        $this->phone6 = $phone;

        return $this;
    }

   
    public function getPhone6()
    {
        return $this->phone6;
    }
    
    public function setPhone7($phone)
    {
        $this->phone7 = $phone;

        return $this;
    }

   
    public function getPhone7()
    {
        return $this->phone7;
    }
    
    public function setPhone8($phone)
    {
        $this->phone8 = $phone;

        return $this;
    }

   
    public function getPhone8()
    {
        return $this->phone8;
    }
    
    public function setPhone9($phone)
    {
        $this->phone9 = $phone;

        return $this;
    }

   
    public function getPhone9()
    {
        return $this->phone9;
    }

    
    /**
     * Set dob
     *
     * @param string $dob
     * @return Contacts
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return string 
     */
    public function getDob()
    {
        return $this->dob;
    }

   

    /**
     * Set company
     *
     * @param string $company
     * @return Contacts
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return Contacts
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return Contacts
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Contacts
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Contacts
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return Contacts
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    /**
     * Get postalcode
     *
     * @return string 
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

   
    /**
     * Set image
     *
     * @param string $image
     * @return Contacts
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    
    /**
     * Set tags
     *
     * @param string $tags
     * @return Contacts
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set contactnotes
     *
     * @param string $contactnotes
     * @return Contacts
     */
    public function setContactnotes($contactnotes)
    {
        $this->contactnotes = $contactnotes;

        return $this;
    }

    /**
     * Get contactnotes
     *
     * @return string 
     */
    public function getContactnotes()
    {
        return $this->contactnotes;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Contacts
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Contacts
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
     public function setVisibility($visibility){
        $this->visibility = $visibility;
        return $this;
    }
    
    public function getVisibility(){
        return $this->visibility;
    }
    
    
}
