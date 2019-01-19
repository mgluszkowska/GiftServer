<?php

class Database{

    public static function connect(){
        $connect = new mysqli("localhost","irizar_martik97","dr~D;Hh%*vMY","irizar_martik97");
	
            if ($connect->connect_error) {
                die('Error : ('. $connect->connect_errno .') '. $connect->connect_error);
            }
            $connect->set_charset("utf8");

        return $connect;
    }
    
}

