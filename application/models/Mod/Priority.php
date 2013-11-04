<?php

class Mod_Priority {

    const LOW = 1;
    const NORMAL = 2;
    const HIGH = 3;
    const URGENT = 4;
    
    private static $_priority_txt = array(1 => "LOW", 2 => "NORMAL", 3 => "HIGH", 4 => "URGENT");

    private static $_priority_view = array(1 => "", 2 => "", 3 => "warning", 4 => "danger");
    
    
    public static function getPriorityName($priority) {
        if (isset(self::$_priority_txt[$priority]))
            return self::$_priority_txt[$priority];
        else
            return "";
    }
    
    public static function getPriorityViewClass($priority) {
       
        if (isset(self::$_priority_view[$priority]))
            return self::$_priority_view[$priority];
        else
            return "";
    }

}

?>
