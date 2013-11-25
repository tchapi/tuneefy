<?php

/* *************************************************** */
/*                                                     */
/*                  CONNECTION HELPER                  */
/*                                                     */
/* *************************************************** */

class DBConnection
{

    private static $_instance;
    private static $failSilently;
    private static $parentCalledFromPost;
    
    // Prevent unconfigured PDO instances!
    private function __construct($config)
    {
        self::$failSilently = $config['failSilently'];
        self::$parentCalledFromPost = $config['parentCalledFromPost'];

        $dsn = sprintf('mysql:dbname=%s;host=%s;', $config['database'], $config['hostname']);
        self::$_instance = new PDO($dsn, $config['username'], $config['password']);    
    }

    public static function db($config = null)
    {
        if(self::$_instance !== null || is_null($config) )
        {
            //We have already stored the object locally so just return it.
            //This is how the object always stays the same
            return self::$_instance;
        }
        
        new DBConnection($config); //Set the instance.
        return self::$_instance;
    }

    public static function error(){

      if (self::$failSilently == true) return false;

      // If we were called from a post request in ajax, we should just echo the error uri
      if (self::$parentCalledFromPost == true) {
        echo _SITE_BASE_URL.'woops';
      } else {
        // Else : standard header:Location
        header('Location: /woops');
      }

    }

}

/* *************************************************** */
/*                                                     */
/*               FIRST CONNECTION ROUTINE              */
/*                                                     */
/* *************************************************** */

$db = DBConnection::db(array(
  'hostname' => "localhost",
  'username' => $user,
  'password' => $password,
  'database' => $database,
  'failSilently' => (!isset($failSilently)?false:$failSilently),
  'parentCalledFromPost' => (!isset($parentCalledFromPost)?false:$parentCalledFromPost)
));
