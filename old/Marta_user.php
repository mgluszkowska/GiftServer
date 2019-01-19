<?php

include_once 'class/main.php';
$connection = Database::connect();

        $users = $connection->prepare(
         "SELECT id, name, surname, email, creationDate from User"
        );
$users->execute();
$users->store_result();
$users->bind_result($id, $name, $surname, $email, $date);

echo "<div class=\"box\"><h3>Uzytkownicy tabela </h3>
        <table> 
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Imie</th>                    
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>";
$licznik = 0;

while ($users->fetch()) {
    $licznik +=1;


    echo "<tr>
<td>$id</td>
<td>$name</td>
<td>$surname</td>
<td>$email</td>
<td>$date</td>

        </tr>";
    
}

echo "</tbody></table></div>";

$connection->close();
