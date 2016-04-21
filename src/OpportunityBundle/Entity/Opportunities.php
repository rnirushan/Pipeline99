<?php

namespace OpportunityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Opportunities
 */
class Opportunities
{
    /**
     * @var string
     */
    private $personname;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $ownedcompany;

    /**
     * @var string
     */
    private $organizationname;

    /**
     * @var string
     */
    private $projectedrevenue;

    /**
     * @var string
     */
    private $accounttype;

    /**
     * @var string
     */
    private $stage;

    /**
     * @var string
     */
    private $producttype;

    /**
     * @var string
     */
    private $opportunitysource;

    /**
     * @var string
     */
    private $closeddate;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $revenue;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var integer
     */
    private $id;
    
    private $forecast;
    
    private $sharing;
    
    private $chanceofsale;
    
    private $currentuserforecast;
    
    private $ownerfullname;
    
    private $userrevenue;
    
    private $contactid;
    
    private $wonnotes;
    
    private $tags;
    
    private $partnername;
    
    private $partnerpercentage;
    
    public function getPartnername(){
        return $this->partnername;
    }
    
    public function setPartnername($partnername){
        $this->partnername = $partnername;
        return $this;
    }
    
    public function getPartnerpercentage() {
        return $this->partnerpercentage;
    }
    
    public function setPartnerpercentage($partnerpercentage) {
        $this->partnerpercentage = $partnerpercentage;
        return $this;
    }


    public function getWonnotes(){
        return $this->wonnotes;
    }
    
    public function setWonnotes($wonnotes){
        $this->wonnotes = $wonnotes;
        return $this;
    }
    
    public function getTags(){
        return $this->tags;
    }
    
    public function setTags($tags){
        $this->tags = $tags;
        return $this;
    }
    
    public function getContactid(){
        return $this->contactid;
    }
    
    public function setContactid($contactid){
        $this->contactid = $contactid;
        return $this;
    }
    
    public function getUserrevenue(){
        return $this->userrevenue;
    }
    
    public function setUserrevenue($userrevenue){
        $this->userrevenue = $userrevenue;
        return $this;
    }
    
    public function getOwnerfullname(){
        return $this->ownerfullname;
    }
    
    public function setOwnerfullname($fullname){
        $this->ownerfullname = $fullname;
        return $this;
    }
    
    public function setCurrentuserforecast($currentuserforecast){
        
        $this-> currentuserforecast = $currentuserforecast;
        
        return $this;
    }
    
    public function getCurrentuserforecast(){
        return $this->currentuserforecast;
    }
    
    public function setChanceofsale($chanceofsale)
    {
        $this->chanceofsale = $chanceofsale;

        return $this;
    }

    public function getChanceofsale()
    {
        return $this->chanceofsale;
    }
    
    
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    public function getForecast()
    {
        return $this->forecast;
    }
    
    public function setSharing($sharing)
    {
        $this->sharing = $sharing;

        return $this;
    }

    public function getSharing()
    {
        return $this->sharing;
    }

    /**
     * Set personname
     *
     * @param string $personname
     * @return Opportunities
     */
    public function setPersonname($personname)
    {
        $this->personname = $personname;

        return $this;
    }

    /**
     * Get personname
     *
     * @return string 
     */
    public function getPersonname()
    {
        return $this->personname;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Opportunities
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
     * Set ownedcompany
     *
     * @param string $ownedcompany
     * @return Opportunities
     */
    public function setOwnedcompany($ownedcompany)
    {
        $this->ownedcompany = $ownedcompany;

        return $this;
    }

    /**
     * Get ownedcompany
     *
     * @return string 
     */
    public function getOwnedcompany()
    {
        return $this->ownedcompany;
    }

    /**
     * Set organizationname
     *
     * @param string $organizationname
     * @return Opportunities
     */
    public function setOrganizationname($organizationname)
    {
        $this->organizationname = $organizationname;

        return $this;
    }

    /**
     * Get organizationname
     *
     * @return string 
     */
    public function getOrganizationname()
    {
        return $this->organizationname;
    }

    /**
     * Set projectedrevenue
     *
     * @param string $projectedrevenue
     * @return Opportunities
     */
    public function setProjectedrevenue($projectedrevenue)
    {
        $this->projectedrevenue = $projectedrevenue;

        return $this;
    }

    /**
     * Get projectedrevenue
     *
     * @return string 
     */
    public function getProjectedrevenue()
    {
        return $this->projectedrevenue;
    }

    /**
     * Set accounttype
     *
     * @param string $accounttype
     * @return Opportunities
     */
    public function setAccounttype($accounttype)
    {
        $this->accounttype = $accounttype;

        return $this;
    }

    /**
     * Get accounttype
     *
     * @return string 
     */
    public function getAccounttype()
    {
        return $this->accounttype;
    }

    /**
     * Set stage
     *
     * @param string $stage
     * @return Opportunities
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return string 
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Set producttype
     *
     * @param string $producttype
     * @return Opportunities
     */
    public function setProducttype($producttype)
    {
        $this->producttype = $producttype;

        return $this;
    }

    /**
     * Get producttype
     *
     * @return string 
     */
    public function getProducttype()
    {
        return $this->producttype;
    }

    /**
     * Set opportunitysource
     *
     * @param string $opportunitysource
     * @return Opportunities
     */
    public function setOpportunitysource($opportunitysource)
    {
        $this->opportunitysource = $opportunitysource;

        return $this;
    }

    /**
     * Get opportunitysource
     *
     * @return string 
     */
    public function getOpportunitysource()
    {
        return $this->opportunitysource;
    }

    /**
     * Set closeddate
     *
     * @param string $closeddate
     * @return Opportunities
     */
    public function setCloseddate($closeddate)
    {
        $this->closeddate = $closeddate;

        return $this;
    }

    /**
     * Get closeddate
     *
     * @return string 
     */
    public function getCloseddate()
    {
        return $this->closeddate;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Opportunities
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     * @return Opportunities
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return string 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Opportunities
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

    /**
     * Set revenue
     *
     * @param string $revenue
     * @return Opportunities
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get revenue
     *
     * @return string 
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return Opportunities
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
