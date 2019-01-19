<?php

include_once 'class/main.php';

//ŁĄCZENIE Z BAZĄ DANYCH
$connection = Database::connect();

//UWAGA, USUWANIE CAŁEJ TABELI
$results = $connection->prepare("DROP TABLE Users ");
$results->execute();

//TWORZENIE TABELI Users
$results = $connection->prepare(
    "CREATE TABLE Users
    (
        id              INT PRIMARY KEY AUTO_INCREMENT,
        name            varchar(15),
        surname         varchar(30),
        email           varchar(30),
        creationDate    DATETIME
    );"
);    
$results->execute();

//USTAWIENIE AUTOINKREMENTACJI
$results = $connection->prepare("ALTER TABLE Users AUTO_INCREMENT=0;");
$results->execute();

//DODAWANIE REKORDÓW DO TABELI
$results = $connection ->prepare("INSERT INTO `Users` (`name`, `surname`, `email`, creationDate) VALUES
('Marta', 'Głuszkowska', 'martik97@gmail.com', NOW()),
('Jan', 'Nowak', 'now123@o2.pl', NOW()),
('Anna', 'Kowalska', 'kowala@gmail.com', NOW());");
$results->execute();
echo "Utworzono tabelę User";

//WYŚWIETLENIE CAŁEJ TABELI
$users = $connection->prepare(
         "SELECT id, name, surname, email, creationDate from Users"
        );
$users->execute();
$users->store_result();
$users->bind_result($id, $name, $surname, $email, $date); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Użytkownicy </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Imie</th>                    
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Data Utworzenia</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($users->fetch()) {
    echo "<tr>
<td>$id</td>
<td>$name</td>
<td>$surname</td>
<td>$email</td>
<td>$date</td>
        </tr>";   
}

echo "</tbody></table></div>";

//ZAPIS DANYCH Z TABELI DO PLIKU XML
//$usersxml = <<<XML
//<?xml version='1.0' encoding=’UTF-8’?
//	<users>
//	</users>
//XML;
//$users = $connection->prepare(
//         "SELECT id, name, surname, email, creationDate from Users"
//        );
//$users->execute();
//$users->store_result();
//$users->bind_result($id, $name, $surname, $email, $date);
//
//while ($users->fetch()) {
//    $users->addChild('user');
//}
//
//$users->asXML("users.xml");


//ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
$connection->close();
