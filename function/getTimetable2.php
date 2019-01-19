<?php

$connection = Database::connect();

$results = $connection->prepare("SELECT t.id, t.data_meczu, t.godzina, r.kod_ligi, t.kolejka, t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie,
                                round(t.typ1,1), round(t.typx,1), round(t.typ2,1), t.kurs1, t.kursx, t.kurs2, m.bramki_gospodarze, m.bramki_goscie, m.walkower, m.anulowany, m.bez_publiki
                                FROM terminarz t
                                    JOIN rozgrywki r ON r.id_rozgrywek = t.id_rozgrywek
                                    JOIN kluby k1 ON k1.id_klubu = t.id_gospodarza
                                    JOIN kluby k2 ON k2.id_klubu = t.id_goscia
                                    LEFT JOIN sila s ON s.id_terminu = t.id
                                    LEFT JOIN mecze m ON m.id_meczu = t.id_meczu
                                WHERE t.data_meczu = '$startDate'
                                    AND t.kurs1 > 0 
                                    AND t.TYP1 > 0  
                                    AND t.pokaz_fwl = 1  
                                    AND t.kolejka > 0
                                ORDER BY t.godzina DESC");

$results->execute();
$results->store_result();
$rows = $results->num_rows;
$results->bind_result($id, $data, $godzina, $liga, $kolejka, $idGospodarza, $gospodarze, $idGoscia, $goscie, $typ1, $typx, $typ2, $kurs1, $kursx, $kurs2, $bramkiGospodarze, $bramkiGoscie, $walkower, $anulowany, $bezPubliki);


//statystyka
	$czas = time();
	$czas_logowania = date("Y-m-d G:i:s", $czas);
	$data_logowania = date("Y-m-d", $czas);
        $stats = $connection->prepare("insert into statystyki
                                    (id, id_strony, strona, czas, data)
                                    values ('','77','FWL_typer_niezalogowani','$czas_logowania','$data_logowania')");
        $stats->execute();
//koniec statystyki	*/

$col = 1;

while ($results->fetch()) {

    $types = array($typ1, $typx, $typ2);
    $max = array_keys($types, max($types));
    $match = date('Y-m-d H:m:s', strtotime($data . " " . $godzina));
    
    if($col == 1) {
    echo "<div class='row'>";
    }
    echo "<div class='match col-lg-6'><div class='matchBox'>";
            
            echo "<p class='matchHeader'><i class='far fa-clock'></i> $godzina</p>
            <div style='padding: 0px 10px;'>
                <table class='table table-sm' style='width:100%'>
                    <tr style='border-bottom: 1px solid #e9ecef;'>
                        <td width='5%;' class='delete-top-border'></td>
                        <td width='70%;' class='delete-top-border '></td>
                        <td width='10%;' class='delete-top-border centerColumn'></td>
                        <td width='15%;' class='delete-top-border centerColumn'><i class=\"far fa-futbol\"></i></td>
                    </tr>
                    <tr style='border-bottom: 1px solid #e9ecef;'>
                        <td><i class='fas fa-home'></i></td>
                        <td>$gospodarze</td>";
    
                        if($bramkiGospodarze > $bramkiGoscie) {
                            echo "<td class='centerColumn'><i class='fas fa-trophy' style='color: gold;'></i></td>";
                        } else {
                            echo "<td class='centerColumn'></td>";
                        }
                        echo "<td class='centerColumn borderCell bold'>$bramkiGospodarze</td>";
                
                    echo "</tr>
                    <tr>
                        <td></td>
                        <td>$goscie</td>";
                    
                        if($bramkiGoscie > $bramkiGospodarze) {
                            echo "<td class='centerColumn'><i class='fas fa-trophy' style='color: gold;'></i></td>";
                        } else {
                            echo "<td class='centerColumn'></td>";
                        }
                        echo "<td class='centerColumn borderCell bold'>$bramkiGoscie</td>";
                        
                    echo "</tr>
                </table>";
            echo "</div>";
            
            echo "<div class=\"row removeMargin\">
                    <div class=\"col-3 divTable\">
                        <div class=\"row p2p10 nl\">
                            Typ
                        </div>
                        <div class=\"row p2p10 nl nowrap\">
                            FwL[%]
                        </div>
                        <div class=\"row p2p10 nl\">
                            Kursy
                        </div>
                        <div class=\"row p2p10 nl\">
                            Wartość
                        </div>
                        <div class=\"row p2p10 \">
                            <i class=\"fas fa-user\"></i>
                        </div>
                    </div>
                    <div class=\"col-3 divTable ";
            
    if ($max[0] == 0) {
        echo "bold ";
    }
    $usersTypes = Typer::getUsersTypes($id,1);
    echo "\" >
                        <div class=\"row p2p10 nl\">
                            1
                        </div>
                        <div class=\"row p2p10 nl\">
                            $typ1 
                        </div>
                        <div class=\"row p2p10 nl\">
                            $kurs1
                        </div>
                        <div class=\"row p2p10 nl\">
                            " . number_format($typ1 * $kurs1/100,2,"."," ") . " 
                        </div>                        
                        <div class=\"row p2p10\">
                            " . number_format($usersTypes,1,"."," ") . "[%] 
                        </div>
                    </div>
                    <div class=\"col-3 divTable ";  

    
    if ($max[0] == 1) {
        echo "bold ";
    }
    echo "\" >
                        <div class=\"row p2p10 nl\">
                            x
                        </div>
                        <div class=\"row p2p10 nl\">
                            $typx 
                        </div>
                        <div class=\"row p2p10 nl\">
                            $kursx
                        </div>
                        <div class=\"row p2p10\">
                            " . number_format($typx * $kursx/100, 2, ".", " ") . " 
                        </div>
                    </div>
                    <div class=\"col-3 divTable ";
    
    if ($max[0] == 2) {
        echo "bold ";
    }
    echo "\" >
                        <div class=\"row p2p10 nl\">
                            2   
                        </div>
                        <div class=\"row p2p10 nl\">
                            $typ2  
                        </div>
                        <div class=\"row p2p10 nl\">
                            $kurs2
                        </div>
                        <div class=\"row p2p10\">
                            " . number_format($typ2 * $kurs2/100, 2, ".", " ") . " 
                        </div>
                    </div>
                </div>";
            if($now < $match) {
                echo "</form>";
            }
        echo "</div>";
        
        if($col == 2){
            echo "</div>";
            $col=0;
        }
        
        echo "</div>";
        
        $col++;
}

if ($col == 2) {
    echo "</div>";
}

mysqli_close($connection);
