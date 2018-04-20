<?php


class GDump
{
    
    public static function d($arg)
    {
        self::dump($arg);
    }
    
    public static function dump($arg)
    {
        if(get_class() == "Graph"){
            
            echo 'Root: '.$arg->getRoot()->getName();
            
            
        } else {
            var_dump($arg);
            echo '<br/>';
        }
    }
    
    
    
}
