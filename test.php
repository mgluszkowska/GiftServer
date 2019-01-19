<?php

require 'rb.php';

// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

//// Wyczyszczenie tabeli 
//// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
R::wipe('test');

//// Tworzenie tabeli item z pierwszym rekordem
$test = R::dispense('test');
$test->user = R::load('user', 1);
$test->item = R::load('item', 1);
R::store($test);

//// Funkcja tworząca obiekt typu wishlist i dodająca go do bazy
//function wishlist($user, $item) {
//    $wishlist = R::dispense('wishlist');
//    $wishlist->user = $user;
//    $wishlist->item = $item;
//    R::store($wishlist);
//}
//
//// Dodanie rekordów do bazy
//wishlist(1,2);
//wishlist(1,3);
//wishlist(3,2);
//wishlist(5,1);

