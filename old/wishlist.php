<?php

include_once 'class/main.php';

//ŁĄCZENIE Z BAZĄ DANYCH
$connection = Database::connect();

//UWAGA, USUWANIE CAŁEJ TABELI
$results = $connection->prepare("DROP TABLE Wishlist ");
$results->execute();

//TWORZENIE TABELI Items
$results = $connection->prepare(
    "CREATE TABLE Wishlist
    (
        id              INT PRIMARY KEY AUTO_INCREMENT,
        user            INT REFERENCES Users(id),
        item            INT REFERENCES Items(id)
    );"
);    
$results->execute();

//USTAWIENIE AUTOINKREMENTACJI
$results = $connection->prepare("ALTER TABLE Wishlist AUTO_INCREMENT=0;");
$results->execute();

//DODAWANIE REKORDÓW DO TABELI
$results = $connection ->prepare("INSERT INTO `Wishlist` (`user`, `item`) VALUES
('1', '1'),
('1', '2'),
('2', '2'),
('2', '3'),
('3', '1'),
('1', '3');");
$results->execute();
echo "Utworzono tabelę Wishlist";

//WYŚWIETLENIE CAŁEJ TABELI
$results = $connection->prepare(
         "SELECT id, user, item from Wishlist"
        );
$results->execute();
$results->store_result();
$results->bind_result($id, $user, $item); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Życzenia </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Id użytkownika</th>                    
                    <th>Id przedmiotu</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$id</td>
<td>$user</td>
<td>$item</td>
        </tr>";   
}

echo "</tbody></table></div>";

$results = $connection->prepare(
         "SELECT `Users`.`name`, `Users`.`surname`, Items.name, Items.price
            FROM `Users`
            INNER JOIN `Wishlist`
            ON `Wishlist`.`user` = `Users`.`id`
            INNER JOIN Items 
            ON Wishlist.item = Items.id
            ORDER BY Users.name"
        );
$results->execute();
$results->store_result();
$results->bind_result($name, $surname, $item, $price); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Lista marzeń</h3>
        <table> 
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>                    
                    <th>Przedmiot</th>
                    <th>Cena</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$name</td>
<td>$surname</td>
<td>$item</td>
<td>$price</td>
        </tr>";   
}

echo "</tbody></table></div>";

//ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
$connection->close();



