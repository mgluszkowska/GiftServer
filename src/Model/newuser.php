<?php

/**
 *  @Entity @Table(name="users1") 
**/

class User {
  /**
 
  * @Id @Column(type="integer")
 
  * @GeneratedValue(strategy="AUTO")
 
  */
 
  protected $id;
 
  /** @Column(length=50, name="name") */
 
  protected $name;
 
  /** @Column(length=80, name="surname") */
 
  protected $surname;
 
 
 /** @Column(length=80, name="email") */
 
  protected $email;
  
  /** @Column(type="date", name="creationDate") */
 
  protected $creationDate;
 
 
  public function getId()
 
  {
 
    return $this->id;
 
  }

 
  public function getName()
 
  {
 
    return $this->name;
 
  }
 
 
 
  public function setName($name)
 
  {
 
   $this->name = $name;
 
  }
 
 
 
  public function getSurname()
 
  {
 
    return $this->surname;
 
  }
 
 
 
  public function setSurname($surname)
 
  {
 
   $this->surname = $surname;
 
  }
  
  public function getEmail()
 
  {
 
    return $this->email;
 
  }
 
 
 
  public function setEmail($email)
 
  {
 
   $this->email = $email;
 
  }
  
  public function getCreationDate()
 
  {
 
    return $this->creationDate;
 
  }
 
 
 
  public function setCreationDate($creationDate)
 
  {
 
   $this->creationDate = $creationDate;
 
  }
 
}