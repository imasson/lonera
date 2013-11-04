<?php

class Tkt_User {

    public static function isvalid() {
        return isset($_SESSION['USER_EMAIL']);
    }

    public static function setUser($email) {
        $_SESSION['USER_EMAIL'] = $email;
        $_SESSION['IS_SUPPORT_USER'] = false;

        $_confini = Common_Config::getInstance();
        $accounts = explode(";", $_confini->email->support->accounts);
        foreach ($accounts as $account) {
            if($account == $email ){
                $_SESSION['IS_SUPPORT_USER'] = true;
            }
        }
    }

    public static function unsetUser() {
        unset($_SESSION['USER_EMAIL']);
         unset($_SESSION['IS_SUPPORT_USER']);
    }

    public static function getUser() {
        return $_SESSION['USER_EMAIL'];
    }

    public static function issupport() {
        if(isset($_SESSION['IS_SUPPORT_USER']))
            return $_SESSION['IS_SUPPORT_USER'];
        
        return false;
    }

}

?>
