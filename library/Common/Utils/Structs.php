<?php
class Common_Utils_Structs
{
    static public function varDump( $struct, $return=false )
    {
        if( $return )
            return print_r($struct,true);
        else
            echo "<pre>".print_r($struct,true)."</pre>";
    }
}
?>
