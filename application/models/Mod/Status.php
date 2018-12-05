<?php

class Mod_Status {

    const WAITING = 1;
    const IN_PROGRESS = 2;
    const RESOLVED = 3;
    const CLOSED = 4;
    const REJECTED = 5;

    private static $_status_txt = array(1 => "WAITING", 2 => "IN PROGRESS", 3 => "RESOLVED", 4 => "CLOSED", 5 => "REJECTED");

    private static $_status_view = array(1 => "label-default", 2 => "label-info", 3 => "label-success", 4 => "label-warning", 5 => "label-danger");
    
    
    public static function getStatusName($status) {
        if (isset(self::$_status_txt[$status]))
            return self::$_status_txt[$status];
        else
            return "";
    }
    
    public static function getStatusViewClass($status) {
        if (isset(self::$_status_view[$status]))
            return self::$_status_view[$status];
        else
            return "";
    }

}

