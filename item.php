<?php

require 'rb.php';
//include_once 'class/main.php';

// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

// Wyczyszczenie tabeli 
// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
//R::wipe('item');

// Tworzenie tabeli item z pierwszym rekordem
$item = R::dispense('item');
$item->name = 'książka "Igrzyska śmierci"';
$item->price = 39.99;
$item->link = 'https://czytam.pl/k,ks_179621,Igrzyska-smierci-Collins-Suzanne-google.html?gclid=CjwKCAiA9K3gBRA4EiwACEhFe_Qh5ijV7znkdqUXqknKYS2cLBSqwrQnGabr_g8vnokhS-n2jWUEMBoCKuAQAvD_BwE';
R::store($item);

// Funkcja tworząca obiekt typu item i dodająca go do bazy
function item($name, $price, $link) {
    $item = R::dispense('item');
    $item->name = $name;
    $item->price = $price;
    $item->link = $link;
    R::store($item);
}

//item('świeczka', 9, 'https://allegro.pl/swieca-swieczka-walec-bialy-biala-6-cm-4-szt-i7415749999.html');
//item('skarpety', 'NULL', 'NULL');

function echoItems() {
    $items = R::getAll("SELECT * FROM item");
    $arrlength = count($items);
    for($x = 0; $x < $arrlength; $x++) {
        echo $items[$x]["id"] ."   " .$items[$x]["name"] ."   ". $items[$x]["price"] ."   ". $items[$x]["link"]."<br>";
    }    
    echo "<br>";
}

//echoItems();

$xml = simplexml_load_file("newitems.xml");
//print_r($xml);
$number_of_items = count($xml->item);
foreach($xml->item as $item){
    $name = $item->name."";
    $price = $item->price."";
    $link = $item->link."";
    item($name, $price, $link);
}

//echo "Tablica items po odczytaniu pliku newitems.xml: <br>";
//echoItems();

$ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
$plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
 
$myfile = fopen("newitems.json", "r");
$str = fread($myfile,filesize("newitems.json"));
$json = str_replace($plstr,$ustr,$str);
$json = json_decode($json);
foreach($json as $item){
    $name = $item->name."";
    $price = $item->price."";
    $link = $item->link."";
    item($name, $price, $link);
}

//echo "Tablica items po odczytaniu pliku newitems.json: <br>";
//echoItems();







