<?php

require 'rb.php';

// Połączenie z bazą
R::setup('mysql:host=localhost;dbname=irizar_martik97', 'irizar_martik97', 'dr~D;Hh%*vMY');

// Wyczyszczenie tabeli 
// (Przy każdym nowym uruchomieniu program dopisuje tych samych ludzi)
//R::wipe('user'); 

// Tworzenie tabeli user i jednoczesne dodanie pierwszego rekordu
// Przy kaźdym uruchomieniu programu dodaje nowego człowieka
$user = R::dispense('user');
$user->name = 'Marta';
$user->surname = 'Głuszkowska';
$user->email = 'martik97@gmail.com';
$user->creationDate = date("Y-m-d h:i:sa");
R::store($user);

function user($name, $surname, $email) {
    $user = R::dispense('user');
    $user->name = $name;
    $user->surname = $surname;
    $user->email = $email;
    $user->creationDate = date("Y-m-d h:i:sa");
    R::store($user);
}

// Dodanie kolejnych danych do tabeli

user('Jan', 'Nowak', 'janow@gmail.com');
user('Anna', 'Kowalska', 'kowal@gmail.com');
user('Test', 'Test', 'test@o2.pl');
user('Ula', 'Brzydula', 'buziaczek@02.pl');
user('Agata', 'Kwiatkowska', 'test@o2.pl');
user('Sławek', 'Bolek', 'test@o2.pl');
user('Róża', 'Zielona', 'test@o2.pl');
user('Paweł', 'Piotrkowski', 'test@o2.pl');
user('Piotr', 'Pawłowski', 'test@o2.pl');
user('Mikołaj', 'Głuszkowski', 'test@o2.pl');
user('Franciszek', 'Smyl', 'test@o2.pl');
user('Magda', 'Jóźkowiak', 'test@o2.pl');
user('Julia', 'Mąka', 'test@o2.pl');
user('Ewa', 'Ogólna', 'test@o2.pl');
user('Maciej', 'Kwadrat', 'test@o2.pl');
user('Zbigniew', 'Mickiewicz', 'test@o2.pl');
user('Aleksandra', 'Słowacka', 'test@o2.pl');
user('Milena', 'Mazowiecka', 'test@o2.pl');
user('Monika', 'Pomorska', 'test@o2.pl');
user('Julia', 'Olsztyn', 'test@o2.pl');
user('Agnieszka', 'Smidt', 'test@o2.pl');
user('Małgorzata', 'Burak', 'test@o2.pl');
user('Lidia', 'Broniec', 'test@o2.pl');
user('Danuta', 'Turowska', 'test@o2.pl');


function echoUsers() {
    $users = R::getAll("SELECT * FROM user");
    $arrlength = count($users);
    for($x = 0; $x < $arrlength; $x++) {
        echo $users[$x]["id"] ."   " .$users[$x]["name"] ."   ". $users[$x]["surname"] ."   ". $users[$x]["email"]."   ".$users[$x]["creation_date"]."<br>";
    }    
    echo "<br>";
}

echoUsers();
// Odczytanie danych o Annie Kowalskiej
$anna = R::load('user', 3);
echo $anna;

// Zmiana danych użytkownika Anna 
$anna->surname = 'Nowak';
$anna->email = 'nowak2@gmail.com';
R::store($anna);

// Ponowne odczytanie danych dla sprawdzenia
$anna = R::load('user', 3);
echo "<br>Po zmianie danych: <br>" .$anna;

echo "<pre>";
// Zapytanie:
// Znalezienie id użytkownika Test
$jan = R::getAll("SELECT id FROM user WHERE name like ? AND surname like ?", array('Test', 'Test'));
$arrlength = count($jan);
echo "<br><br>";
for($x = 0; $x < $arrlength; $x++) {
    echo "Id : " .$jan[$x]["id"];
    echo "<br>";
}

// Sprawdzenie liczby rekordów w tabeli
echo "<br>Liczba rekordów w tabeli: " .R::count('user');

// Usunięcie użytkownika Ula
$toDelete = R::load('user', 4);
R::trash($toDelete);

// Ponowne sprawdzenie liczby rekordów
echo "<br>Liczba rekordów w tabeli po usunięciu użytkownika: " .R::count('user');
