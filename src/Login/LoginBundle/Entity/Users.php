<?php

namespace Login\LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 */
class Users
{
    /**
     * @var int     
     */
    private $id;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $companyname;

    /**
     * @var string
     */
    private $companysize;

    /**
     * @var string
     */
    private $status;
    
    private $username;

    private $industry;
    
    private $firstname;
    
    private $lastname;
    
    private $role;
    
    private $telephoneoffice;
    
    private $telephonemobile;
    
    private $jobtitle;
    
    private $address1;
    
    private $address2;
    
    private $city;
    
    private $state;
    
    private $postalcode;
    
    private $dob;
    
    private $contractstartdate;
    
    private $commissionoriginator;
    
    private $commissionnonoriginator;
    
    private $annualdraw;
    
    private $earninggoal;
    
    private $profilenote;
    
    private $twitter;
    
    private $linkedin;
    
    private $individualforecast;
    
    private $projectedrevenue;
    
    private $noofopportunity;
    
    private $userview;
    
    private $opportunityview;
    
    private $contactview;
    
    private $wondealcount;
   
    private $lossdealcount;
    
    private $opendealcount;
    
    private $image;
    
    public function getImage(){
        return $this->image;
    }
    
    public function setImage($image){
        $this->image = $image;
        return $this;
    }
    
    public function getWondealcount(){
        return $this->wondealcount;
    }
    
    public function setWondealcount($count){
        $this->wondealcount =$count;
        return $this;
    }
    
    public function getLossdealcount(){
        return $this->lossdealcount;
    }
    
    public function setLossdealcount($count){
        $this->lossdealcount = $count;
        return $this;
    }

    public function getOpendealcount(){
        return $this->opendealcount;
    }
    
    public function setOpendealcount($count){
        $this->opendealcount = $count;
        return $this;
    }

    public function getUserview(){
        return $this->userview;
    }
    
    public function getOpportunityview(){
        return $this->opportunityview;
    }
    
    public function getContactview() {
        return $this->contactview;
    }

    public function setUserview($userview){
        $this->userview = $userview;
        return $this;
    }
    
    public function setOpportunityview($opportunityview){
        $this->opportunityview = $opportunityview;
        return $this;
    }
    
    public function setContactview($contactview){
        $this->contactview = $contactview;
        return $this;
    }
    
    public function getNoofopportunity() {
        return $this->noofopportunity;
    }
    
    public function setNoofopportunity($noofopportunity){
        $this->noofopportunity = $noofopportunity;
        return $this;
    }
    
    public function getIndividualforecast(){
        return $this->individualforecast;
    }
    
    public function getProjectedrevenue(){
        return $this->projectedrevenue;
    }
    
    public function setIndividualforecast($individualforecast){
        $this->individualforecast = $individualforecast;
        return $this;
    }
    
    public function setProjectedrevenue($projectedrevenue){
        $this->projectedrevenue = $projectedrevenue;
        return $this;
        
    }

    public function getStatus(){
        return $this->status;
    }
    
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }


    public function setTelephoneoffice($telephone){
        $this->telephoneoffice = $telephone;
        return $this;
    }
    
    public function getTelephoneoffice(){
        return $this->telephoneoffice;
    }
    
    public function setTelephonemobile($telephone){
        $this->telephonemobile = $telephone;
        return $this;
    }
    
    public function getTelephonemobile(){
        return $this->telephonemobile;
    }
    
    public function setJobtitle($jobtitle){
        $this->jobtitle = $jobtitle;
        return $this;
    }
    
    public function getJobtitle(){
        return $this->jobtitle;
    }
    
    public function setAddress1($address){
        $this->address1 = $address;
        return $this;
    }
    
    public function getAddress1(){
        return $this->address1;
    }
    
    public function setAddress2($address){
        $this->address2 = $address;
        return $this;
    }
    
    public function getAddress2(){
        return $this->address2;
    }
    
    public function setCity($city){
        $this->city = $city;
        return $this;
    }
    
    public function getCity(){
        return $this->city;
    }
    
     public function setState($state){
        $this->state = $state;
        return $this;
    }
    
    public function getState(){
        return $this->state;
    }
    
     public function setPostalcode($code){
        $this->postalcode = $code;
        return $this;
    }
    
    public function getPostalcode(){
        return $this->postalcode;
    }
    
    public function setDob($dob){
        $this->dob = $dob;
        return $this;
    }
    
    public function getDob(){
        return $this->dob;
    }
    
    public function setContractstartdate($date){
        $this->contractstartdate = $date;
        return $this;
    }
    
    public function getContractstartdate(){
        return $this->contractstartdate;
    }
    
    public function setCommissionoriginator($commission){
        $this->commissionoriginator = $commission;
        return $this;
    }
    
    public function getCommissionoriginator(){
        return $this->commissionoriginator;
    }
    
    public function setCommissionnonoriginator($commission){
        $this->commissionnonoriginator = $commission;
        return $this;
    }
    
    public function getCommissionnonoriginator(){
        return $this->commissionnonoriginator;
    }
    
    public function setAnnualdraw($draw){
        $this->annualdraw = $draw;
        return $this;
    }
    
    public function getAnnualdraw(){
        return $this->annualdraw;
    }
    
    public function setEarninggoal($goal){
        $this->earninggoal = $goal;
        return $this;
    }
    
    public function getEarninggoal(){
        return $this->earninggoal;
    }
    
    public function setProfilenote($note){
        $this->profilenote = $note;
        return $this;
    }
    
    public function getProfilenote(){
        return $this->profilenote;
    }
    
    public function setTwitter($twitter){
        $this->twitter = $twitter;
        return $this;
    }
    
    public function getTwitter(){
        return $this->twitter;
    }
    
    public function setLinkedin($linkedin){
        $this->linkedin = $linkedin;
        return $this;
    }
    
    public function getLinkedin(){
        return $this->linkedin;
    }

    public function setFirstname($firstname){
        $this->firstname = $firstname;
         return $this;
    }

    public function getFirstname(){
          return $this->firstname;
     }
     
    public function setLastname($lastname){
        $this->lastname = $lastname;
         return $this;
    }

    public function getLastname(){
          return $this->lastname;
     } 
     
    public function setRole($role){
        $this->role = $role;
         return $this;
    }

    public function getRole(){
          return $this->role;
     } 

    public function setIndustry($industry){
        $this->industry = $industry;
         return $this;
    }

    public function getIndustry(){
          return $this->industry;
     }
    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set companyname
     *
     * @param string $companyname
     * @return Users
     */
    public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;

        return $this;
    }

    /**
     * Get companyname
     *
     * @return string 
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * Set companysize
     *
     * @param string $companysize
     * @return Users
     */
    public function setCompanysize($companysize)
    {
        $this->companysize = $companysize;

        return $this;
    }

    /**
     * Get companysize
     *
     * @return string 
     */
    public function getCompanysize()
    {
        return $this->companysize;
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

 public function setUsername($username)
    {
        
        $this->username = $username;
        
        return $this;
    }
    
    public function getId(){
        
        return $this->id;
        
    }
}
