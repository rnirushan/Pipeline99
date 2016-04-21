<?php
namespace Login\LoginBundle\Modals;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author Sirojan-T
 */
class Login {
    //put your code here
    private $username;
    private $password;
    private $role;
    private $fullname;
    
    public function getFullname(){
        return $this->fullname;
    }
    
    public function setFullname($fullname){
        $this->fullname = $fullname;
    }


    public function getUsername(){
        return $this->username;
    }
    
    public function setUsername($username){
        
        $this->username = $username;
    }
    
    public function getPassword(){
        
        return $this->password;
        
    }
    
    public function setPassword($password){
        $this->password = $password;
        
    }
    
    public function getRole(){
        return $this->role;
        
    }
    
    public function setRole($role){
        $this->role = $role;
    }
    
    
    
    
    
}
