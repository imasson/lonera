<?php
class Common_User 
{
    const _NS_SESSION = "FLAT_NS_AUTH";
    
    private $_ns_auth = null;
    private static $instance = null;
    
    private function __constructor()
    {
        $_SESSION[self::_NS_SESSION] = new stdClass();
    }
    
    /**
     * 
     * @return Common_User
     */
    public static function getInstance()
    {
        if( self::$instance === null )
        {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
 
    public function setValid($_user)
    {
        //var_dump($_SESSION[self::_NS_SESSION]);
        if( !isset($_SESSION[self::_NS_SESSION]) )
            $_SESSION[self::_NS_SESSION] = new stdClass();
        
        $_SESSION[self::_NS_SESSION]->isValidSession = true;
        $_SESSION[self::_NS_SESSION]->userdata = $_user;
    }
    
    public function logout()
    {
        unset($_SESSION[self::_NS_SESSION]);
    }
    
    public function isValid()
    {
        if( isset($_SESSION[self::_NS_SESSION]->isValidSession) )
            return $_SESSION[self::_NS_SESSION]->isValidSession;
        
        return false;
    }
    
    public function getFullUserName()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return $_SESSION[self::_NS_SESSION]->userdata["fullname"];
        }
        
        return false;
        
    }
    
    public function getId()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return $_SESSION[self::_NS_SESSION]->userdata["id"];
        }
        
        return false;   
    }
    
    public function getCredentials()
    {
        if( isset($_SESSION[self::_NS_SESSION]) )
        {
            return array("username" => $_SESSION[self::_NS_SESSION]->userdata["username"],
                         "password" => $_SESSION[self::_NS_SESSION]->userdata["password"]); 
        }
        
        return false;   
    }
    
    
}
?>
