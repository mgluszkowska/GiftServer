<?php

//include_once 'function/loginCheck.php';
//$id_zawodnika = 2277;
//$connection = Database::connect(); 

$dane_zawodnika = $connection->prepare("select data_urodzenia, miejsce_urodzenia, pozycja, wzrost, waga, link, zdjecie_zrodlo,TIMESTAMPDIFF(YEAR, data_urodzenia, CURDATE()) AS wiek_zawodnika
 from zawodnicy where id_zawodnika=$id_zawodnika "); //$_POST[id_zawodnika]

$dane_zawodnika->execute();
$dane_zawodnika->store_result();
$rows = $dane_zawodnika->num_rows;

if ($rows > 0){
//$dane_zawodnika->bind_result($data_urodzenia, $miejsce_urodzenia, $pozycja, $wzrost, $waga, $link_do_zdjecia, $zdjecie_zrodlo, $wiek_zawodnika);		
	if ($link_do_zdjecia ==''){$link_do_zdjecia ='/IMG/pilkarze/missing_foto.png';}
}
$sezony = $connection->prepare("
	SELECT sezony.id_sezonu, sezon, id_klubu, id_rozgrywek
	FROM sklady 
        join mecze on sklady.id_meczu = mecze.id_meczu 
        join sezony on mecze.id_sezonu = sezony.id_sezonu
	WHERE
	sklady.id_zawodnika = $id_zawodnika and
	sezony.id_sezonu > 0 and 
	((mecze.id_rozgrywek <= 6 and mecze.id_rozgrywek != 3) or mecze.id_rozgrywek = 17 or mecze.id_rozgrywek = 18 or mecze.id_rozgrywek = 15)
	group by sezony.id_sezonu, id_klubu
	order by sezony.id_sezonu desc, mecze.runda desc, mecze.kolejka desc");
$sezony->execute();
$sezony->store_result();
$rows2 = $sezony->num_rows;

if ($rows2 > 0){
$sezony->bind_result($id_sezonu, $sezon, $id_klubu, $id_rozgrywek);		
}

$now = date('Y-m-d H:m:s', strtotime('now'));
//statystyka
$czas = time();
$czas_logowania = date("Y-m-d G:i:s", $czas);
$data_logowania = date("Y-m-d", $czas);
$stats = $connection->prepare("insert into statystyki
                                    (id, id_strony, strona, czas, data, id_uzytkownika)
                                    values ('','67','FWL_zawodnik_razem','$czas_logowania','$data_logowania', '$_SESSION[userId]')");
$stats->execute();
//koniec statystyki	
/*
//statystyka
	$czas = time();
	$czas_logowania = date("Y-m-d G:i:s", $czas);
	$data_logowania = date("Y-m-d", $czas);
	$dodaj_log="insert into statystyki_zawodnicy_regionu
	(id, zawodnik, id_zawodnika, czas, data, id_uzytkownika, login)
	values ('','$imie_nazwisko','$id_zawodnika','$czas_logowania','$data_logowania', '$_SESSION[userId]', '". $user->getUserId() ."')";
	if (1==0)//if($id_zawodnika>0)
	{
	//mysql_query($dodaj_log) or die(mysql_error());
//        $dodaj_log->execute();
	}
//koniec statystyki	
*/
//bgcolor=whitesmoke
        
while ($dane_zawodnika->fetch()) {
    	$wizytowka .="<div class='playerBox'>
	<table  align=left width=auto>
	<tr><td colspan=3 align=center><b>$imie_nazwisko</b></td></tr>
	<tr><td colspan=3><font size=2>";
	if ($zdjecie_zrodlo !='')
	{
	$wizytowka .="
	zdjecie: ";
	}
	$wizytowka .="
	<a target=\"_blank\" href=\"$zdjecie_zrodlo\" >$zdjecie_zrodlo</a>	
	</td></tr>
	<tr><td rowspan=9 valign=top align=left><img src=$link_do_zdjecia></td>

	<td align=right> data urodzenia (wiek):</td><td align=center><b> $data_urodzenia ($wiek_zawodnika) </b></td></tr>";
	if ($miejsce_urodzenia !='')
	{$wizytowka .="
	<tr><td align=right> miejsce urodzenia :</td><td align=center><b> $miejsce_urodzenia</b></td></tr>";
	} else {$wizytowka .="
	<tr><td align=right> miejsce urodzenia :</td><td align=center><b> - </b></td></tr>";
	}
	if ($pozycja !='brak_danych')
	{$wizytowka .="
	<tr><td align=right> pozycja :</td><td align=center><b> $pozycja</b></td></tr>";
	} else {$wizytowka .="
	<tr><td align=right> pozycja :</td><td align=center><b> - </b></td></tr>";
	}
	if ($wzrost !=0)
	{$wizytowka .="
	<tr><td align=right> wzrost :</td><td align=center><b> $wzrost cm</b></td></tr>";
	} else {$wizytowka .="
	<tr><td align=right> wzrost :</td><td align=center><b> - </b></td></tr>";
	}
	if ($waga !=0)
	{$wizytowka .="
	<tr><td align=right> waga :</td><td align=center><b> $waga kg</b></td></tr>";
	} else {$wizytowka .="
	<tr><td align=right> waga :</td><td align=center><b> - </b></td></tr>";
	}
        
//	$wizytowka .="	
	//<tr><td  align=right> liczba ligowych gier<b>*</b> :</td><td align=center><b> $liczba_gier_razem</b></td></tr>
	//<tr><td  align=right> czas gry [minuty]* :</td><td align=center><b> $czas_gry_razem'</b></td></tr>
	//<tr><td  align=right> bramki * :</td><td align=center>";
	//if ($bramki_razem == 0) {
	//$wizytowka .= "</td>";
	//} else
	//{
	//$wizytowka .= "<b> $bramki_razem</b></td>";
	//}
        //
	$wizytowka .= "</tr>
	</table></div>";
    echo $wizytowka;
}

            echo "<thead>
                <tr>
                    
                    <th>Sezon - klub</th>
                    <th>Czas</th>                    
                    <th>Rank</th>
                    <th>Rank<br>czas</th>
                    <th>Mecze<br>(z-r-p)</th>                    
                    <th>Gole</th>
                </tr>
            </thead>";

while ($sezony->fetch()) {
    $nazwa_klubu = Tools::nazwa_klubu($id_klubu, $id_sezonu);

//analiza z-r-p
		$sql4=$connection->prepare("SELECT id_gospodarza, id_goscia, bramki_gospodarze, bramki_goscie, id_rozgrywek, id_klubu
                    FROM mecze join sklady on sklady.id_meczu = mecze.id_meczu
		WHERE mecze.id_sezonu = $id_sezonu and
		mecze.id_rozgrywek = $id_rozgrywek and
		sklady.id_zawodnika = $id_zawodnika and
		sklady.id_klubu = $id_klubu");
                $sql4->execute();
                $sql4->store_result();
                $z = 0;
		$r = 0;
		$p = 0;
//                $rows4 = $sql4->num_rows;
//                if ($rows4 > 0){
                    $sql4->bind_result($id_gospodarza, $id_goscia, $bramki_gospodarze, $bramki_goscie, $id_rozgrywek, $klub_zawodnika);
                   

//		$czas_punkty = 0;
			while($sql4->fetch())
			{
			$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			if($id_rozgrywek == 17) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 18) {$wartosc_ligi = 6;}
				if ($id_gospodarza == $klub_zawodnika)
				{
					if($bramki_gospodarze > $bramki_goscie){$z+=1;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;}
					if($bramki_gospodarze < $bramki_goscie){$p+=1;}
				} else
				{
					if($bramki_gospodarze > $bramki_goscie){$p+=1;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;}
					if($bramki_gospodarze < $bramki_goscie){$z+=1;}
				}
			}
             //   }
//			$czas_punkty = 	(integer) $czas_punkty;
//			$czas_punkty += ranking_bramki_ligowe_w_sezonie ($_POST[id_zawodnika], $id_sezonu, $id_klubu);
//			$ranking_razem += $czas_punkty;
			//$liczba_gier_razem += $liczba_gier;
			//$czas_gry_razem += $czas_gry;
			$z_razem += $z;
			$r_razem += $r;
			$p_razem += $p;
//koniec analizy z-r-p        

    echo "<tr><td>$sezon - $nazwa_klubu </td>";
    
    $sql3 = $connection->prepare(
        "SELECT 
	sum(czas_na_boisku)AS czas_gry, count(sklady.id_zawodnika) AS liczba_gier
        ,(SELECT count( id_bramki ) as bramki
	FROM mecze m1
	JOIN bramki b ON b.id_meczu = m1.id_meczu
	WHERE m1.id_sezonu = $id_sezonu
	AND m1.id_rozgrywek = $id_rozgrywek
	AND b.id_zawodnika = $id_zawodnika
	and b.bramka_dla_klubu = $id_klubu) as bramki
	FROM zawodnicy, sklady, mecze
	WHERE zawodnicy.id_zawodnika =sklady.id_zawodnika and sklady.id_meczu = mecze.id_meczu 
	and mecze.id_rozgrywek = $id_rozgrywek
	and mecze.id_sezonu = $id_sezonu
	and sklady.id_klubu = $id_klubu
	and sklady.id_zawodnika = $id_zawodnika"); //IN ( 1, 2, 4, 5, 6 , 17, 18, 15 )
    $sql3->execute();
    $sql3->store_result();
    $rows2 = $sql3->num_rows;
    if ($rows2 > 0){
        $sql3->bind_result($czas_gry, $liczba_gier, $bramki);
        $sql3->fetch();
        $rank = Rank::ranking_zawodnika($id_zawodnika,$id_sezonu,$id_klubu, $id_rozgrywek);
        if ($czas_gry>0){
            $rank_czas = number_format($rank/$czas_gry,2);
        }

        echo "<td> $czas_gry' </td>";
        echo "<td> $rank </td>";
        echo "<td> $rank_czas </td>";
        echo "<td> $liczba_gier ($z-$r-$p)</td>";
        echo "<td> $bramki </td>";    
    }
echo "</tr>";
}

echo "<tr><td colspan=6></td></tr>";    
//mysqli_close($connection);

