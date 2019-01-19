<?php

require 'rb.php';


// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

//// Wyczyszczenie tabeli 
//// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
//R::wipe('wishlist');

//// Tworzenie tabeli item z pierwszym rekordem
$wishlist = R::dispense('wishlist');
$wishlist->user = R::load('user', 1);
$wishlist->item = R::load('item', 1);
R::store($wishlist);

// Funkcja tworząca obiekt typu wishlist i dodająca go do bazy
function wishlist($user, $item) {
    $wishlist = R::dispense('wishlist');
    $wishlist->user = $user;
    $wishlist->item = $item;
    R::store($wishlist);
}

// Dodanie rekordów do bazy
wishlist(R::load('user', 1),R::load('item', 2));
wishlist(R::load('user', 1),R::load('item', 3));
wishlist(R::load('user', 3),R::load('item', 2));
wishlist(R::load('user', 5),R::load('item', 1));
//var_dump($wishlist);

function echoWishlist() {
    $items = R::getAll("SELECT * FROM wishlist");
    $arrlength = count($items);
    for($x = 0; $x < $arrlength; $x++) {
        echo $items[$x]["id"] ."   " .$items[$x]["user_id"] ."   ". $items[$x]["item_id"] ."<br>";
    }    
    echo "<br>";
}

echoWishlist();

