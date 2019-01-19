<?php

//require 'rb.php';
require 'item.php';
//R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');



if ($_SERVER['REQUEST_METHOD']== "POST") {
    
//    $ch = curl_init();
//
//    curl_setopt($ch, CURLOPT_URL, 'https://giftmanagerppr.herokuapp.com/newgift.html');
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_POST, 1);
    
//include_once('html.php'); //where HttpRequest.php is the saved file
//$url= 'http://www.google.com/';
//$r = new HttpRequest($url, "POST");
//var_dump($r->send());  
    
    $name =   $_POST["name"];
    $price = $_POST["price"];
    if (isset($_POST['link'])) {
        $link = $_POST["link"];
    }
    else {
        $link = 'NULL';
    }
    
	item($name, $price, $link);
        http_response_code(201);
        header("X-Sample-Test: foo");
        
        //$message = "Item " .$name . " added to database";
        echo "Item " .$name . " added to database";
        
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
//        
//        $output = curl_exec($ch);
//        
//        if ($output === FALSE) {
//            echo "curl error " . curl_error($ch);
//        }
//        
//        curl_close($ch);
//        
//        print_r($output);
}

?>