<?php

$startDate = null;
if ($_GET) {
    $startDate = $_GET['selectedDate'];
}
if ($_POST) {
    $startDate = $_POST['date'];
}

$connection = Database::connect();

$before = $connection->prepare("SELECT DISTINCT t.data_meczu
                                FROM terminarz t
                                WHERE t.kurs1 > 0 
                                    AND t.TYP1 > 0  
                                    AND t.pokaz_fwl = 1  
                                    AND t.kolejka > 0
                                    AND t.data_meczu > '" . date("Y/m/d") . "'
                                ORDER BY t.data_meczu ASC
                                LIMIT 2");

$before->execute();
$before->store_result();
$before->bind_result($date);

echo "<div align='center' style='display: -webkit-box;'><nav style='font-size: 12px;'><ul class='pagination'>";

$before->fetch();
$second = $date;
$before->fetch();
$first = $date;

if ($startDate == $first) {
    echo "<li class='page-item active nowrap'>
                    <span class='page-link'>
                        $first<span class='sr-only'>(current)</span>
                    </span>
                </li>";
} else {
    echo "<li class='page-item nowrap'>
            <a class='page-link' href='?selectedDate=$first'>$first</a>
        </li>";
}

if ($startDate == $second) {
    echo "<li class='page-item active nowrap'>
                    <span class='page-link'>
                        $second<span class='sr-only'>(current)</span>
                    </span>
                </li>";
} else {
    echo "<li class='page-item nowrap'>
            <a class='page-link' href='?selectedDate=$second'>$second</a>
        </li>";
}


$after = $connection->prepare("SELECT DISTINCT t.data_meczu
                                FROM terminarz t
                                WHERE t.kurs1 > 0 
                                    AND t.TYP1 > 0  
                                    AND t.pokaz_fwl = 1  
                                    AND t.kolejka > 0
                                    AND t.data_meczu <= '" . date("Y/m/d") . "'
                                ORDER BY t.data_meczu DESC
                                LIMIT 3");

$after->execute();
$after->store_result();
$after->bind_result($date);

while ($after->fetch()) {
    if ($startDate == null && strtotime($date) <= strtotime('now')) {
        $startDate = $date;
        echo "<li class='page-item active nowrap'>
                <span class='page-link'>
                    $date<span class='sr-only'>(current)</span>
                </span>
            </li>";
    } else {
        if ($startDate == $date) {
            echo "<li class='page-item active nowrap'>
                    <span class='page-link'>
                        $date<span class='sr-only'>(current)</span>
                    </span>
                </li>";
        } else {
            echo "<li class='page-item nowrap'>
                    <a class='page-link' href='?selectedDate=$date'>$date</a>
                </li>";
        }
    }
}


echo "</ul></nav></div>";

mysqli_close($connection);
