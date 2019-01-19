<?php

include_once 'class/main.php';

//ŁĄCZENIE Z BAZĄ DANYCH
$connection = Database::connect();

//UWAGA, USUWANIE CAŁEJ TABELI
$results = $connection->prepare("DROP TABLE Benefactors ");
$results->execute();

//TWORZENIE TABELI Benefactors
$results = $connection->prepare(
    "CREATE TABLE Benefactors
    (
        id              INT PRIMARY KEY AUTO_INCREMENT,          
        person          INT REFERENCES Users(id),   
        benefactor      INT REFERENCES Users(id)
    );"
);    
$results->execute();

//USTAWIENIE AUTOINKREMENTACJI
$results = $connection->prepare("ALTER TABLE Benefactors AUTO_INCREMENT=0;");
$results->execute();

//DODAWANIE REKORDÓW DO TABELI
$results = $connection ->prepare("INSERT INTO `Benefactors` (`person`, benefactor) VALUES
('1', '2'),
('2', '3'),
('3', '1');");
$results->execute();
echo "Utworzono tabelę Benefactors";

//WYŚWIETLENIE CAŁEJ TABELI
$results = $connection->prepare(
         "SELECT id, person, benefactor from Benefactors"
        );
$results->execute();
$results->store_result();
$results->bind_result($id, $person, $benefactor); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Obdarowujący </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Kto</th>                    
                    <th>Komu</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$id</td>
<td>$person</td>
<td>$benefactor</td>
        </tr>";   
}

echo "</tbody></table></div>";

$results = $connection->prepare(
         "SELECT U1.name as 'imie osoby', U1.surname as 'nazwisko osoby', "
        . "U2.name as 'imie darczyncy', U2.surname as 'nazwisko darczyncy' "
        . "FROM Benefactors INNER JOIN Users U1 ON U1.id = Benefactors.person "
        . "INNER JOIN Users U2 ON U2.id = Benefactors.benefactor"
        );
$results->execute();
$results->store_result();
$results->bind_result($name1, $surname1, $name2, $surname2); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Obdarowujący</h3>
        <table> 
            <thead>
                <tr>
                    <th>Imię osoby</th>
                    <th>Nazwisko osoby</th>                    
                    <th>Imię obdarowującego</th>
                    <th>Nazwisko obdarowującego</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$name1</td>
<td>$surname1</td>
<td>$name2</td>
<td>$surname2</td>
        </tr>";   
}

echo "</tbody></table></div>";

//ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
$connection->close();





