<?php

require 'json.php';

if ($_SERVER['REQUEST_METHOD']== "POST") {
    $person =   $_POST["id"];
    $filename = "user".$person.".json";
    //echo "Will soon create wishlist for user ".$person." and store it in ".$filename;
    wishlistToJson($filename, $person);
    $url = "http://irizar.ehost.pl/martik97/".$filename;
    echo $url;
}

