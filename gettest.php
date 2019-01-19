<?php

require 'rb.php';

R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

function user($name, $surname, $email) {
    $user = R::dispense('user');
    $user->name = $name;
    $user->surname = $surname;
    $user->email = $email;
    $user->creationDate = date("Y-m-d h:i:sa");
    R::store($user);
}

if ($_SERVER['REQUEST_METHOD']== "GET") {
	$anna = R::load('user', 3);
        echo $anna;
} else if ($_SERVER['REQUEST_METHOD']== "POST") {
    $name =   $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
	user($name, $surname, $email);
        echo "User added to database";
}




//
//// Połączenie z bazą

//

//

//
////echo "Incoming: " .$name;
//
////user($name, $surname, $email);

?>