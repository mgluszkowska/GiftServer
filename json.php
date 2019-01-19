<?php

include_once 'class/main.php';
require 'rb.php';

R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

function usersToJson($filename) {
    
    $jsonfile = $filename;
    $fh = fopen($jsonfile, 'w');
    
    $data = R::getAll("SELECT id, name, surname, email, creation_date from user");
    //var_dump($data);
    
    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
    $string = str_replace($ustr,$plstr,json_encode($data));
    
    fwrite($fh, $string);
    fclose($fh);
    
    //echo "Utworzono plik " .$filename ."<br>";
}

function itemsToJson($filename) {
    $jsonfile = $filename;
    $fh = fopen($jsonfile, 'w');
    
    $data = R::getAll("SELECT id, name, price, link from item");
    
    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
    $string = str_replace($ustr,$plstr,json_encode($data));
   
    //$string = "<pre>".json_encode($data);
    
    fwrite($fh, $string);
    fclose($fh);
    
    //echo "Utworzono plik " .$filename ."<br>";
}

function wishlistToJson($filename, $person) {
    $jsonfile = $filename;
    $fh = fopen($jsonfile, 'w');
    
    $data = R::getAll("SELECT * FROM item WHERE id in (SELECT item_id FROM wishlist where user_id like ".$person." )");
    
    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
    $string = str_replace($ustr,$plstr,json_encode($data));
   
    //$string = "<pre>".json_encode($data);
    
    fwrite($fh, $string);
    fclose($fh);
    
    //echo "Utworzono plik " .$filename ."<br>";
}

function benefactorsToJson($filename) {
    $jsonfile = $filename;
    $fh = fopen($jsonfile, 'w');
    
    $data = R::getAll("SELECT id, user_id, benefactor_id from benefactor");
    
    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
    $string = str_replace($ustr,$plstr,json_encode($data));
    
    fwrite($fh, $string);
    fclose($fh);
    
    //echo "Utworzono plik " .$filename ."<br>";
}

function claimsToJson($filename) {
    $jsonfile = $filename;
    $fh = fopen($jsonfile, 'w');
    
    $data = R::getAll("SELECT id, item_id, who_id, forwho_id from claims");
    
    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
    $string = str_replace($ustr,$plstr,json_encode($data));
    
    fwrite($fh, $string);
    fclose($fh);
    
    //echo "Utworzono plik " .$filename ."<br>";
}

usersToJson("users.json");
itemsToJson("items.json");
wishlistToJson("user1.json", 1);
benefactorsToJson("benefactors.json");
claimsToJson("claims.json");
