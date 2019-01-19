<?php

require 'rb.php';


// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

//// Wyczyszczenie tabeli 
//// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
//R::wipe('benefactor');

//// Tworzenie tabeli benefactor z pierwszym rekordem
$benefactor = R::dispense('benefactor');
$benefactor->user =R::load('user', 1);
$benefactor->benefactor = R::load('user', 2);
R::store($benefactor);

// Funkcja tworząca obiekt typu benefactor i dodająca go do bazy
function benefactor($u1, $u2) {
    $benefactor = R::dispense('benefactor');
    $benefactor->user = $u1;
    $benefactor->benefactor = $u2;
    R::store($benefactor);
}

// Dodanie rekordów do bazy
benefactor(R::load('user', 2), R::load('user', 3));
benefactor(R::load('user', 3), R::load('user', 1));


function echoBenefactors() {
    $items = R::getAll("SELECT * FROM benefactor");
    $arrlength = count($items);
    for($x = 0; $x < $arrlength; $x++) {
        echo $items[$x]["id"] ."   " .$items[$x]["user_id"] ."   ". $items[$x]["benefactor_id"] ."<br>";
    }    
    echo "<br>";
}

echoBenefactors();