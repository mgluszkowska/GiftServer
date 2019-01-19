<?php

include_once 'class/main.php';

function itemsWithLinks() {
    //ŁĄCZENIE Z BAZĄ DANYCH
    $connection = Database::connect();
    // Zapytanie 
    // Wyświetlenie tylko tych przedmiotów, które mają linki
    $results = $connection->prepare(
             "SELECT id, name, price, link FROM item WHERE link != 'NULL'"
            );
    $results->execute();
    $results->store_result();
    $results->bind_result($id, $name, $price, $link); //PRZYPISANIE DANYCH DO ZMIENNYCH

    echo "<div class=\"box\"><h3>Przedmioty, które mają linki </h3>
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
}

function showTableBenefactor() {
    //ŁĄCZENIE Z BAZĄ DANYCH
    $connection = Database::connect();
    
   // Złączenie tabel, aby zamiast id osób w tabeli benefactor wyświetlały się imiona i nazwiska
    $results = $connection->prepare(
             "SELECT U1.name as 'imie osoby', U1.surname as 'nazwisko osoby', "
            . "U2.name as 'imie darczyncy', U2.surname as 'nazwisko darczyncy' "
            . "FROM benefactor INNER JOIN user U1 ON U1.id = benefactor.user_id "
            . "INNER JOIN user U2 ON U2.id = benefactor.benefactor_id"
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
}

function showTableWishlist() {
    //ŁĄCZENIE Z BAZĄ DANYCH
    $connection = Database::connect();
    $results = $connection->prepare(
         "SELECT user.name, user.surname, item.name, item.price
            FROM  user
            INNER JOIN wishlist
            ON wishlist.user_id = user.id
            INNER JOIN item 
            ON wishlist.item_id = item.id
            ORDER BY user.name"
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
}

function showTableClaims() {
    //ŁĄCZENIE Z BAZĄ DANYCH
    $connection = Database::connect();
    $results = $connection->prepare(
            "SELECT item.name , U1.name as 'zamawiajacy', U2.name as 'dla kogo'
               FROM user U1
               INNER JOIN claims
               ON claims.who_id = U1.id
               INNER JOIN item 
               ON claims.item_id = item.id
               INNER JOIN user U2
               ON U2.id = claims.forwho_id"
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
}

function showWishlistOfUser($id) {
    $connection = Database::connect();
    $results = $connection->prepare(
         "SELECT user.id, user.name, user.surname, item.name, item.price, item.link
            FROM  user
            INNER JOIN wishlist
            ON wishlist.user_id = user.id
            INNER JOIN item 
            ON wishlist.item_id = item.id
            WHERE user.id LIKE " .$id 
            ." ORDER BY user.name"
        );
    $results->execute();
    $results->store_result();
    $results->bind_result($id, $name, $surname, $item, $price, $link); //PRZYPISANIE DANYCH DO ZMIENNYCH

    echo "<div class=\"box\"><h3>Lista marzeń użytkownika o id 1:</h3>
            <table> 
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>                    
                        <th>Przedmiot</th>
                        <th>Cena</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>";
    while ($results->fetch()) {
        echo "<tr>
    <td>$id</td>
    <td>$name</td>
    <td>$surname</td>
    <td>$item</td>
    <td>$price</td>
    <td>$link</td>
            </tr>";   
    }
    echo "</tbody></table></div>";
    
    //ZAMKNIĘCIE POŁACZENIA Z BAZĄ DANYCH
    $connection->close();
}

itemsWithLinks();
showTableBenefactor();
showTableWishlist();
showTableClaims();
showWishlistOfUser(1);



