<?php

require 'rb.php';

// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

//// Wyczyszczenie tabeli 
//// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
//R::wipe('claims');

//// Tworzenie tabeli claims z pierwszym rekordem
$claims = R::dispense('claims');
$claims->item = R::load('item', 1);
$claims->who = R::load('user', 1);
$claims->forwho = R::load('user', 3);
R::store($claims);

// Funkcja tworząca obiekt typu claims i dodająca go do bazy
function claims($item, $u1, $u2) {
    $claims = R::dispense('claims');
    $claims->item = $item;
    $claims->who = $u1;
    $claims->forwho = $u2;
    R::store($claims);
}

// Dodanie rekordów do bazy
claims(R::load('item', 2), R::load('user', 2), R::load('user', 1));
claims(R::load('item', 1), R::load('user', 3), R::load('user', 1));
claims(R::load('item', 3), R::load('user', 3), R::load('user', 2));


function echoClaims() {
    $items = R::getAll("SELECT * FROM claims");
    $arrlength = count($items);
    for($x = 0; $x < $arrlength; $x++) {
        echo $items[$x]["id"] ."   " .$items[$x]["item_id"] ."   ". $items[$x]["who_id"] ."   " .$items[$x]["forwho_id"] ."<br>";
    }    
    echo "<br>";
}

echoClaims();