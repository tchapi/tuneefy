<?php

/* *************************************************** */
/*                                                     */
/*                  CONNECTION HELPER                  */
/*                                                     */
/* *************************************************** */

class DBConnection {

  private $link; 
  private $server;
  private $username;
  private $password;
  private $dbname;
  
  private $failSilently;
  private $parentCalledFromPost;
  
  public function __construct($server, $username, $password, $dbname, $failSilently = false, $parentCalledFromPost = false) {
    $this->server = $server;
    $this->username = $username;
    $this->password = $password;
    $this->dbname = $dbname;
    
    $this->failSilently = $failSilently;
    $this->parentCalledFromPost = $parentCalledFromPost;
  }
  
  public function connect() 
  { 
      $this->link = mysql_connect($this->server, $this->username, $this->password); 
      
      if ( !($this->link) ) {
      
        $this->error();
        
      } else {
      
        return true;
        
      }
  }
  
  public function setFailSilently($failSilently)
  {
    $this->failSilently = $failSilently;
  }
  
  public function setParentCalledFromPost($parentCalledFromPost)
  {
    $this->parentCalledFromPost = $parentCalledFromPost;
  }

  public function selectdb()
  {
    $success	= mysql_select_db($this->dbname, $this->link);
    
    if ( !$success ) {
      
      $this->error();
      
    } else {
    
      return true;
      
    }
  }

  public function error() 
  { 
      // Closes the current connection
      mysql_close($this->link); 
      
      // In the event that we do not want an error to be thrown to the end-user, we fail silently and we don't return true
      if ($this->failSilently == true) return false;
      
      // If we were called from a post request in ajax, we should just echo the error uri
      if ($this->parentCalledFromPost == true) {
        echo _SITE_BASE_URL.'woops';
      } else {
        // Else : standard header:Location
        header('Location: /woops');
      }
      
      die(0);
  }
  
}


/* *************************************************** */
/*                                                     */
/*                  CONNECTION ROUTINE                 */
/*                                                     */
/* *************************************************** */


if (!isset($parentCalledFromPost)) $parentCalledFromPost = false;
if (!isset($failSilently)) $failSilently = false;

$DBConnection = new DBConnection("localhost", $user, $password, $database, $failSilently, $parentCalledFromPost);

if ($DBConnection->connect()) $DBConnection->selectdb();
