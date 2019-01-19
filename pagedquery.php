<?php

include_once 'class/main.php';

function pagedQuery() {
    //ŁĄCZENIE Z BAZĄ DANYCH
    $connection = Database::connect();

    $results_per_page = 10;

    // sprawdzenie ile rekordów jest w tabeli
    $users = mysqli_query($connection,
             "SELECT id, name, surname, email, creation_date from user"
            );
    $number_of_results = mysqli_num_rows($users) ."<br>";

    //ustalenie liczby stron
    $number_of_pages = ceil($number_of_results/$results_per_page);

    // ustalenie strony, na której jesteśmy
    if(!isset($_GET['page'])) {
        $page = 1;
    }
    else {
        $page = $_GET['page'];
    }

    // ustalenie początkowego numeru dla każdej strony
    $this_page_first_result = ($page-1)*$results_per_page;

    $sql = "SELECT id, name, surname, email, creation_date from user"
            . " LIMIT " .$this_page_first_result .',' .$results_per_page;
    $users = mysqli_query($connection, $sql);


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
    while ($row = mysqli_fetch_array($users)) {
        echo "<tr>
    <td>$row[id]</td>
    <td>$row[name]</td>
    <td>$row[surname]</td>
    <td>$row[email]</td>
    <td>$row[creation_date]</td>
            </tr>";   
    }
    echo "</tbody></table></div><br>";

    //wyświetlenie linków do stron
    for ($page=1; $page<=$number_of_pages; $page++) {
        echo '<a href="pagedquery.php?page=' .$page .'">' .$page .'</a> ';
    }
}

pagedQuery();



