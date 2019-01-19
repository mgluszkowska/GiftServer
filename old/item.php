<?php

include_once 'class/main.php';

//ŁĄCZENIE Z BAZĄ DANYCH
$connection = Database::connect();

//UWAGA, USUWANIE CAŁEJ TABELI
$results = $connection->prepare("DROP TABLE Items ");
$results->execute();

//TWORZENIE TABELI Items
$results = $connection->prepare(
    "CREATE TABLE Items
    (
        id              INT PRIMARY KEY AUTO_INCREMENT,
        name            varchar(50),
        price           FLOAT(2),
        link            varchar(50) DEFAULT 'NULL'
    );"
);    
$results->execute();

//USTAWIENIE AUTOINKREMENTACJI
$results = $connection->prepare("ALTER TABLE Items AUTO_INCREMENT=0;");
$results->execute();

//DODAWANIE REKORDÓW DO TABELI
$results = $connection ->prepare("INSERT INTO `Items` (`name`, `price`) VALUES
('książka: Rośliny ogrodowe', '39.99'),
('zegarek damski złoty', '69.90'),
('skarpety męskie czarne', '7.68');");
$results->execute();
$results = $connection ->prepare("INSERT INTO `Items` (`name`, `price`, link) VALUES
('naszyjnik', '54.99', 'http://nene.sklep.pl/zloty-naszyjnik-gwiazd-celebrytka-blaszka-grawer.html');");
$results->execute();
echo "Utworzono tabelę Items";

//WYŚWIETLENIE CAŁEJ TABELI
$results = $connection->prepare(
         "SELECT id, name, price, link from Items"
        );
$results->execute();
$results->store_result();
$results->bind_result($id, $name, $price, $link); //PRZYPISANIE DANYCH DO ZMIENNYCH

echo "<div class=\"box\"><h3>Przedmioty </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nazwa</th>                    
                    <th>Cena</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>";

//KAŻDA ITERACJA TO JEDEN REKORD W TABELI
while ($results->fetch()) {
    echo "<tr>
<td>$id</td>
<td>$name</td>
<td>$price</td>
<td>$link</td>
        </tr>";   
}

echo "</tbody></table></div>";

//ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
$connection->close();

