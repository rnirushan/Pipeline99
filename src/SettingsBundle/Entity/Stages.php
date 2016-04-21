<?php

namespace SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stages
 */
class Stages
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $companyname;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $salechance;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set username
     *
     * @param string $username
     * @return Stages
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
     * Set companyname
     *
     * @param string $companyname
     * @return Stages
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
     * Set name
     *
     * @param string $name
     * @return Stages
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
     * Set salechance
     *
     * @param string $salechance
     * @return Stages
     */
    public function setSalechance($salechance)
    {
        $this->salechance = $salechance;

        return $this;
    }

    /**
     * Get salechance
     *
     * @return string 
     */
    public function getSalechance()
    {
        return $this->salechance;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Stages
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
     * Set status
     *
     * @param string $status
     * @return Stages
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
