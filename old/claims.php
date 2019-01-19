<?php

include_once 'class/main.php';

//ŁĄCZENIE Z BAZĄ DANYCH
$connection = Database::connect();

//UWAGA, USUWANIE CAŁEJ TABELI
$results = $connection->prepare("DROP TABLE Claims ");
$results->execute();

//TWORZENIE TABELI Claims
$results = $connection->prepare(
    "CREATE TABLE Claims
    (
        id              INT PRIMARY KEY AUTO_INCREMENT,          
        item            INT REFERENCES Items(id),
        forUser         INT REFERENCES Users(id),   
        claimedBy       INT REFERENCES Users(id)
    );"
);    
$results->execute();

//USTAWIENIE AUTOINKREMENTACJI
$results = $connection->prepare("ALTER TABLE Claims AUTO_INCREMENT=0;");
$results->execute();

//DODAWANIE REKORDÓW DO TABELI
$results = $connection ->prepare("INSERT INTO `Claims` (`item`, `forUser`, claimedBy) VALUES
('1', '3', '1'),
('2', '1', '2'),
('1', '1', '3'),
('3', '2', '3');");
$results->execute();
echo "Utworzono tabelę Claims";

//WYŚWIETLENIE CAŁEJ TABELI
$results = $connection->prepare(
         "SELECT id, item, forUser, claimedBy from Claims"
        );
$results->execute();
$results->store_result();
$results->bind_result($id, $item, $forUser, $claimedBy); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Rezerwacje </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Id przedmiotu</th>                    
                    <th>Dla kogo</th>
                    <th>Kto rezerwuje</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$id</td>
<td>$item</td>
<td>$forUser</td>
<td>$claimedBy</td>
        </tr>";   
}

echo "</tbody></table></div>";

$results = $connection->prepare(
         "SELECT `Items`.`name` , U1.`name` as 'zamawiajacy', U2.name as 'dla kogo'
            FROM `Users` U1
            INNER JOIN Claims
            ON Claims.`claimedBy` = U1.`id`
            INNER JOIN Items 
            ON Claims.item = Items.id
            INNER JOIN Users U2
            ON U2.id = Claims.forUser"
        );
$results->execute();
$results->store_result();
$results->bind_result($item, $name, $for); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Rezerwacje</h3>
        <table> 
            <thead>
                <tr>
                    <th>Przedmiot</th>
                    <th>Zamawiajacy</th> 
                    <th>Dla kogo</th> 
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$item</td>        
<td>$name</td>
<td>$for</td>

        </tr>";   
}

echo "</tbody></table></div>";

//ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
$connection->close();





