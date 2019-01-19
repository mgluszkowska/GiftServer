<?php

include_once 'function/loginCheck.php';

$connection = Database::connect();

$results = $connection->prepare("SELECT t.id, t.data_meczu, t.godzina, r.kod_ligi, t.kolejka, t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie,
                                round(t.typ1,1), round(t.typx,1), round(t.typ2,1), t.kurs1, t.kursx, t.kurs2, m.bramki_gospodarze, m.bramki_goscie, m.walkower, m.anulowany, m.bez_publiki, typy.bramki_gospodarze, typy.bramki_goscie, typy.id_typu
                                FROM terminarz t
                                    JOIN rozgrywki r ON r.id_rozgrywek = t.id_rozgrywek
                                    JOIN kluby k1 ON k1.id_klubu = t.id_gospodarza
                                    JOIN kluby k2 ON k2.id_klubu = t.id_goscia
                                    LEFT JOIN sila s ON s.id_terminu = t.id
                                    LEFT JOIN mecze m ON m.id_meczu = t.id_meczu
                                    LEFT JOIN fwl_typowania typy ON typy.id_uzytkownika = " . $user->getUserId() . " AND typy.id_terminu = t.id
                                WHERE t.data_meczu = '$startDate'
                                    AND t.kurs1 > 0 
                                    AND t.TYP1 > 0  
                                    AND t.pokaz_fwl = 1  
                                    AND t.kolejka > 2
                                ORDER BY t.godzina DESC");

$results->execute();
$results->store_result();
$rows = $results->num_rows;
$results->bind_result($id, $data, $godzina, $liga, $kolejka, $idGospodarza, $gospodarze, $idGoscia, $goscie, $typ1, $typx, $typ2, $kurs1, $kursx, $kurs2, $bramkiGospodarze, $bramkiGoscie, $walkower, $anulowany, $bezPubliki, $homeBet, $guestBet, $idTypu);

$now = date('Y-m-d H:m:s', strtotime('now'));

$col = 1;

while ($results->fetch()) {

    $types = array($typ1, $typx, $typ2);
    $max = array_keys($types, max($types));
    $match = date('Y-m-d H:m:s', strtotime($data . " " . $godzina));
    
    if($col == 1) {
    echo "<div class='row'>";
    }
    echo "<div class='match col-lg-6'><div class='matchBox'>";
            if($now < $match) {
                echo "<form action='index.php' method='post' id='$id'>";
            }
            echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />
            <input type=\"hidden\" id=\"date\" name=\"date\" value=\"$data\" />
            <input type=\"hidden\" id=\"time\" name=\"time\" value=\"$godzina\" />
            <input type=\"hidden\" id=\"kurs1\" name=\"kurs1\" value=\"$kurs1\" />
            <input type=\"hidden\" id=\"kurs2\" name=\"kurs2\" value=\"$kurs2\" />
            <input type=\"hidden\" id=\"kursx\" name=\"kursx\" value=\"$kursx\" />
            <p class='matchHeader'><i class='far fa-clock'></i> $godzina</p>
            <div style='padding: 0px 10px;'>
                <table class='table table-sm' style='width:100%'>
                    <tr style='border-bottom: 1px solid #e9ecef;'>
                        <td width='5%;' class='delete-top-border'></td>
                        <td width='55%;' class='delete-top-border '></td>
                        <td width='10%;' class='delete-top-border centerColumn'></td>
                        <td width='15%;' class='delete-top-border centerColumn'><i class=\"far fa-futbol\"></i></td>
                        <td width='15%;' class='delete-top-border centerColumn'><i class=\"fas fa-user\"></i></td>
                    </tr>
                    <tr style='border-bottom: 1px solid #e9ecef;'>
                        <td><i class='fas fa-home'></i></td>
                        <td>$gospodarze</td>";
    
                        if($now < $match) {
                            echo "<td class='centerColumn'></td>";
                            echo "<td class='centerColumn'></td>";
                            echo "<td class='centerColumn'>";
                                if(!is_null($homeBet)) {
                                    echo "<select class=\"selectSmall\" id=\"homeBet\" name=\"homeBet\" required>";
                                        for ($i = 0; $i < 16; $i++) {
                                            if($homeBet == $i){
                                                echo "<option selected>$i</option>";
                                            } else {
                                                echo "<option>$i</option>";
                                            }
                                        } 
                                    echo "</select>";
                                } else {
                                    echo "<select class=\"selectSmall\" id=\"homeBet\" name=\"homeBet\" required>";
                                        echo "<option></option>";
                                        for ($i = 0; $i < 16; $i++) {
                                            echo "<option>$i</option>";
                                        } 
                                    echo "</select>";
                                }
                                echo "</td>";
                            echo "<td class='centerColumn'></td>";
                        } else {
                            if($bramkiGospodarze > $bramkiGoscie) {
                                echo "<td class='centerColumn'><i class='fas fa-trophy' style='color: gold;'></i></td>";
                            } else {
                                echo "<td class='centerColumn'></td>";
                            }
                            echo "<td class='centerColumn borderCell bold'>$bramkiGospodarze</td>";
                            echo "<td class='centerColumn borderCell bold ";
                            if(is_numeric($homeBet)) {
                                if($homeBet == $bramkiGospodarze) {
                                    echo "win";
                                } else {
                                    echo "lose";
                                }
                            }
                            echo "'>$homeBet</td>";
                        }
                    echo "</tr>
                    <tr>
                        <td></td>
                        <td>$goscie</td>";
                            
                        if($now < $match) {
                            echo "<td class='centerColumn'></td>";
                            echo "<td class='centerColumn'></td>";
                            echo "<td class='centerColumn'>";
                                if(!is_null($guestBet)) {
                                    echo "<select class=\"selectSmall\" id=\"guestBet\" name=\"guestBet\" required>";
                                        for ($i = 0; $i < 16; $i++) {
                                            if($guestBet == $i){
                                                echo "<option selected>$i</option>";
                                            } else {
                                                echo "<option>$i</option>";
                                            }
                                        } 
                                    echo "</select>";
                                } else {
                                    echo "<select class=\"selectSmall\" id=\"guestBet\" name=\"guestBet\" required>";
                                        echo "<option></option>";
                                        for ($i = 0; $i < 16; $i++) {
                                            echo "<option>$i</option>";
                                        } 
                                    echo "</select>";
                                }
                                echo "</td>";
                            echo "<td class='centerColumn'></td>";
                        } else {
                            if($bramkiGoscie > $bramkiGospodarze) {
                                echo "<td class='centerColumn'><i class='fas fa-trophy' style='color: gold;'></i></td>";
                            } else {
                                echo "<td class='centerColumn'></td>";
                            }
                            echo "<td class='centerColumn borderCell bold'>$bramkiGoscie</td>";
                            echo "<td class='centerColumn borderCell bold ";
                            if(is_numeric($guestBet)) {
                                if($guestBet == $bramkiGoscie) {
                                    echo "win";
                                } else {
                                    echo "lose";
                                }
                            }
                            echo "'>$guestBet</td>";
                        }
                    echo "</tr>
                </table>";
                if($now < $match) {
                    if(is_null($homeBet) && is_null($guestBet)){
                        echo "<button class='btn btn-sm btn-block whiteButton clickable' id=\"type\" name=\"type\" type='submit'>TYPUJ WYNIK</button>";
                    } else {
                        echo "<button class='btn btn-sm btn-block whiteButton clickable' id=\"change\" name=\"change\" type='submit'>ZMIEŃ TYPOWANIE</button>";
                    }
                }
            echo "</div>";
            
            echo "<div class=\"row removeMargin\">
                    <div class=\"col-3 divTable\">
                        <div class=\"row p2p10 nl\">
                            Typ 1X2
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
                            user types
                        </div>
                    </div>
                    <div ";
    
    if ($now < $match) {
        echo "onclick='submitType($id, \"type1\");' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Typuj wygraną gospodarzy\" class=\"col-3 divTable typeBox clickable ";
        if ($idTypu == 1) {
            echo "betBorder ";
        }
    } else {
        echo "class=\"col-3 divTable typeBox ";
        if ($idTypu == 1) {
            if(is_null($homeBet)){
                if ($bramkiGospodarze > $bramkiGoscie) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            } else {
                if ($homeBet > $guestBet) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            }
        }
    }
    if ($max[0] == 0) {
        echo "bold ";
    }
    echo "\" >
                        <div class=\"row p2p10 nl\">
                            1
                        </div>
                        <div class=\"row p2p10 nl\">
                        " . number_format($typ1,0,"."," ") . "[%]
                        </div>
                        <div class=\"row p2p10 nl\">
                            $kurs1
                        </div>
                        <div class=\"row p2p10\">
                        " . number_format($typ1 * $kurs1/100,2,"."," ") . " 
                        </div>
                    </div>
                    <div ";  
    if ($now < $match) {
        echo "onclick='submitType($id, \"typex\");' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Typuj remis\" class=\"col-3 divTable typeBox clickable ";
        if ($idTypu == 3) {
            echo "betBorder ";
        }
    } else {
        echo "class=\"col-3 divTable typeBox ";
        if ($idTypu == 3) {
            if(is_null($homeBet)){
                if ($bramkiGospodarze == $bramkiGoscie) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            } else {
                if ($homeBet == $guestBet) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            }
        }
    }
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
                            " . number_format($typ1 * $kurs1/100,2,"."," ") . " 
                        </div>
                    </div>
                    <div ";  
    if ($now < $match) {
        echo "onclick='submitType($id, \"type2\");' data-toggle=\"tooltip\" data-placement=\"top\" title=\"Typuj wygraną gości\" class=\"col-3 divTable typeBox clickable ";
        if ($idTypu == 2) {
            echo "betBorder ";
        }
    } else {
        echo "class=\"col-3 divTable typeBox ";
        if ($idTypu == 2) {
            if(is_null($homeBet)){
                if ($bramkiGospodarze < $bramkiGoscie) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            } else {
                if ($homeBet < $guestBet) {
                    echo "betBorder win ";
                } else {
                    echo "betBorder lose ";
                }
            }
        }
    }
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
                            " . number_format($typ1 * $kurs1/100,2,"."," ") . " 
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
