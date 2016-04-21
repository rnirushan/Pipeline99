<?php

namespace NotesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notes
 */
class Notes
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $priority;

    /**
     * @var string
     */
    private $due;

    /**
     * @var string
     */
    private $visibility;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $tags;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $typeid;

    /**
     * @var string
     */
    private $username;

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @var integer
     */
    private $id;
    
    private $status;
    
    private $companyname;
    
    private $fullname;
    
    public function setFullname($fullname){
        
        $this->fullname = $fullname;

        return $this;
    }
    
    public function getFullname()
    {
        return $this->fullname;
    }
    
     public function setCompanyname($companyname)
    {
        $this->companyname = $companyname;

        return $this;
    }

   
    public function getCompanyname()
    {
        return $this->companyname;
    }
    
     public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Notes
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Notes
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set due
     *
     * @param string $due
     * @return Notes
     */
    public function setDue($due)
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due
     *
     * @return string 
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     * @return Notes
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
     * Set notes
     *
     * @param string $notes
     * @return Notes
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
     * Set tags
     *
     * @param string $tags
     * @return Notes
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
     * Set type
     *
     * @param string $type
     * @return Notes
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set typeid
     *
     * @param integer $typeid
     * @return Notes
     */
    public function setTypeid($typeid)
    {
        $this->typeid = $typeid;

        return $this;
    }

    /**
     * Get typeid
     *
     * @return integer 
     */
    public function getTypeid()
    {
        return $this->typeid;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Notes
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

   
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    
    public function getTimestamp()
    {
        return $this->timestamp;
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
