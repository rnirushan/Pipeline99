<?php

namespace TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 */
class Tasks
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
    private $assignto;

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
    private $tasktype;

    /**
     * @var integer
     */
    private $tasktypeid;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $companyname;

    /**
     * @var integer
     */
    private $id;
    
    private $fullname;
    
    public function setFullname($fullname){
        
        $this->fullname = $fullname;

        return $this;
    }
    
    public function getFullname()
    {
        return $this->fullname;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * Set assignto
     *
     * @param string $assignto
     * @return Tasks
     */
    public function setAssignto($assignto)
    {
        $this->assignto = $assignto;

        return $this;
    }

    /**
     * Get assignto
     *
     * @return string 
     */
    public function getAssignto()
    {
        return $this->assignto;
    }

    /**
     * Set visibility
     *
     * @param string $visibility
     * @return Tasks
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
     * @return Tasks
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
     * @return Tasks
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
     * Set tasktype
     *
     * @param string $tasktype
     * @return Tasks
     */
    public function setTasktype($tasktype)
    {
        $this->tasktype = $tasktype;

        return $this;
    }

    /**
     * Get tasktype
     *
     * @return string 
     */
    public function getTasktype()
    {
        return $this->tasktype;
    }

    /**
     * Set tasktypeid
     *
     * @param integer $tasktypeid
     * @return Tasks
     */
    public function setTasktypeid($tasktypeid)
    {
        $this->tasktypeid = $tasktypeid;

        return $this;
    }

    /**
     * Get tasktypeid
     *
     * @return integer 
     */
    public function getTasktypeid()
    {
        return $this->tasktypeid;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Tasks
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
     * Set timestamp
     *
     * @param string $timestamp
     * @return Tasks
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return string 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Tasks
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
     * Set companyname
     *
     * @param string $companyname
     * @return Tasks
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
