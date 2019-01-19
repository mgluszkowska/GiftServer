<?php

$data_poczatkowa = '2016-01-01';
$liczba_dni_pokaz = 7;
$limit_dzisiaj = 15;
include("funkcje.php");

$polaczenie = mysql_connect("localhost", "irizar_irizar", "zawisza") or die('I cannot connect to the database because: ' . mysql_error());
mysql_select_db("irizar_irizar");


if ($_POST[filtruj] == 'yes') {
    $zapytanie_o_terminy = "select t.id, t.data_meczu, t.id_meczu, t.godzina, t.czas, t.id_sezonu, t.id_rozgrywek, t.id_grupy, t.kolejka, t.runda,
 t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie, 
kurs1, kursx, kurs2, typ1, typx, typ2, s.sila1total, s.sila2total, s.id_terminu as s_id_terminu, s.strefa, s.wynik, s.strefa_1, s.strefa_x, s.strefa_2, t.uwagi
from terminarz t
join kluby k1 on k1.id_klubu = t.id_gospodarza
join kluby k2 on k2.id_klubu = t.id_goscia
left join sila s on s.id_terminu = t.id
where (id_gospodarza = $_POST[id_gospodarza] or id_goscia = $_POST[id_goscia])
AND KURS1 > 0
AND TYP1>0
order by 2 desc
limit 10
";
} else {
    $t = getdate();
    $today = date('Y-m-d', $t[0]);
    $yesterday = date('Y-m-d', $t[0] - 86400);
    $tomorrow = date('Y-m-d', $t[0] + 86400);

    IF ($_SESSION[wybrana_data] == 'next') {
        $zapytanie_ile_dzisiaj = "select t.id
	from terminarz t
	where t.data_meczu = '$today' and kurs1 >0 and TYP1>0 and pokaz_fwl = 1 and kolejka >2";
        $pobrane_ile_dzisiaj = mysql_query($zapytanie_ile_dzisiaj) or die(mysql_error());
        if (mysql_num_rows($pobrane_ile_dzisiaj) > $limit_dzisiaj)
            $zapytanie_o_terminy = "select t.id, t.data_meczu, t.id_meczu, t.godzina, t.czas, t.id_sezonu, t.id_rozgrywek, t.id_grupy, t.kolejka, t.runda,
 t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie, 
kurs1, kursx, kurs2, typ1, typx, typ2, s.sila1total, s.sila2total, s.id_terminu as s_id_terminu, s.strefa, s.wynik, s.strefa_1, s.strefa_x, s.strefa_2, t.uwagi
from terminarz t
join kluby k1 on k1.id_klubu = t.id_gospodarza
join kluby k2 on k2.id_klubu = t.id_goscia
left join sila s on s.id_terminu = t.id
where
t.data_meczu = '$today' and
kurs1 >0 and
TYP1>0 and 
pokaz_fwl = 1 and 
kolejka >2
order by data_meczu, godzina";
        else
            $zapytanie_o_terminy = "select t.id, t.data_meczu, t.id_meczu, t.godzina, t.czas, t.id_sezonu, t.id_rozgrywek, t.id_grupy, t.kolejka, t.runda,
 t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie, 
kurs1, kursx, kurs2, typ1, typx, typ2, s.sila1total, s.sila2total, s.id_terminu as s_id_terminu, s.strefa, s.wynik, s.strefa_1, s.strefa_x, s.strefa_2, t.uwagi
from terminarz t
join kluby k1 on k1.id_klubu = t.id_gospodarza
join kluby k2 on k2.id_klubu = t.id_goscia
left join sila s on s.id_terminu = t.id
where
t.data_meczu >= CURDATE() and 
t.data_meczu <= DATE_SUB(CURDATE(),INTERVAL - $liczba_dni_pokaz DAY) and
kurs1 >0 and
TYP1>0 and 
pokaz_fwl = 1 and 
kolejka >2
order by data_meczu , godzina 
limit $limit_dzisiaj";
    }
// $liczba_dni_pokaz
//t.data_meczu <= DATE_SUB(CURDATE(),INTERVAL - 1 DAY) and
    IF ($_SESSION[wybrana_data] == 'hist') {
        $zapytanie_o_terminy = "select t.id, t.data_meczu, t.id_meczu, t.godzina, t.czas, t.id_sezonu, t.id_rozgrywek, t.id_grupy, t.kolejka, t.runda,
 t.id_gospodarza, k1.nazwa_klubu as gospodarze, t.id_goscia, k2.nazwa_klubu as goscie, 
kurs1, kursx, kurs2, typ1, typx, typ2, s.sila1total, s.sila2total, s.id_terminu as s_id_terminu, s.strefa, s.wynik, s.strefa_1, s.strefa_x, s.strefa_2, t.uwagi
from terminarz t
join kluby k1 on k1.id_klubu = t.id_gospodarza
join kluby k2 on k2.id_klubu = t.id_goscia
left join sila s on s.id_terminu = t.id
where
t.data_meczu < CURDATE() and 
$warunek_daty
kurs1 >0 and
TYP1>0 and
pokaz_fwl = 1 and 
kolejka >2
order by data_meczu desc , godzina desc
limit 50";
    }
// $liczba_dni_pokaz
//t.data_meczu <= DATE_SUB(CURDATE(),INTERVAL - 1 DAY) and
}

//where id_sezonu = $_POST[wybrany_id_sezonu] and id_rozgrywek = $_POST[wybrany_id_rozgrywek] and id_grupy = $_POST[wybrany_id_grupy]
//<input type=\"hidden\" name=\"filtruj\" value=\"yes\">	
$pobrana_lista_terminow = mysql_query($zapytanie_o_terminy) or die(mysql_error());
$terminarz .= "
	<table class=\"table table-striped\">
	<tr>
	<th nowrap> data meczu <br>
	godzina </th>
	<th nowrap>liga </th>
	<th>kol </th>
	<th>gospodarze <br>: <br> goscie </th>
	<th>wynik <br>?<br>:<br> ?</th>
	<th>typy FwL<br> 1 <br> x <br> 2 </th>
	<th>kursy<br> k1 <br> kx <br> k2 </th>
	</tr>";


$licznik_strefa = 0;

$liczba_meczow = 0;
$liczba_trafien = 0;
$suma_trafionych_kursow = 0;
$suma_trafionych_kursow_1 = 0;
$suma_trafionych_kursow_x = 0;
$suma_trafionych_kursow_2 = 0;
$yield_singli = 0;
$yield_singli_1 = 0;
$yield_singli_x = 0;
$yield_singli_2 = 0;
$liczba_1 = 0;
$liczba_x = 0;
$liczba_2 = 0;
$liczba_typowanych_1 = 0;
$liczba_typowanych_x = 0;
$liczba_typowanych_2 = 0;
$liczba_trafionych_1 = 0;
$liczba_trafionych_x = 0;
$liczba_trafionych_2 = 0;

while ($rek1 = mysql_fetch_array($pobrana_lista_terminow)) {
    $id_meczu = null;
    $bramki_gospodarze = null;
    $bramki_goscie = null;
    $s_wynik = null;
    $wynik = null;

    $id_sezonu = $rek1['id_sezonu'];
    $id_terminu = $rek1['id'];
    $data_meczu = $rek1['data_meczu'];
    $godzina = substr($rek1['godzina'], 0, 5);
    //20160618 $id_meczu = $rek1['id_meczu'];
    $t_id_meczu = $rek1['id_meczu'];
    $runda = $rek1['runda'];
    $id_rozgrywek = $rek1['id_rozgrywek'];
    $id_gospodarza = $rek1['id_gospodarza'];
    //$gospodarze = $rek1['gospodarze'];
    $gospodarze = nazwa_klubu_w_sezonie($id_gospodarza, $id_sezonu);
    $id_goscia = $rek1['id_goscia'];
    //$goscie = $rek1['goscie'];
    $goscie = nazwa_klubu_w_sezonie($id_goscia, $id_sezonu);
    $kolejka = $rek1['kolejka'];
    $typ1 = $rek1['typ1'];
    $typx = $rek1['typx'];
    $typ2 = $rek1['typ2'];
    $kurs1 = $rek1['kurs1'];
    $kursx = $rek1['kursx'];
    $kurs2 = $rek1['kurs2'];
    $sila1total = $rek1['sila1total'];
    $sila2total = $rek1['sila2total'];
    $s_id_terminu = $rek1['s_id_terminu'];
    $s_strefa = $rek1['strefa'];
    $s_wynik = $rek1['wynik'];
    $strefa_1 = $rek1['strefa_1'];
    $strefa_x = $rek1['strefa_x'];
    $strefa_2 = $rek1['strefa_2'];
    $uwagi = $rek1['uwagi'];
    if ($sila2total == 0) {
        $sila2total = 0.01;
    }
//sprawdanie najwiekszego typu
    if ($typ1 > $typx) {
        if ($typ1 > $typ2) {
            $typmax = '1';
        }
        //if ($typ2 != null) {$typmax = '2';}
        else {
            $typmax = '2';
        }
    } else
    if ($typx > $typ2) {
        $typmax = 'X';
    }
    //if ($typ2 != null) {$typmax = '2';}
    else {
        $typmax = '2';
    }
//obliczanie Yield start
    $liczba_meczow += 1;
    /* 	if ( $s_wynik == '1')
      {$liczba_1 += 1;
      if ($typmax == '1')
      {$liczba_trafionych_1 += 1; $liczba_trafien += 1; $suma_trafionych_kursow += $kurs1; $suma_trafionych_kursow_1 += $kurs1;
      $yield_singli_1 = ($suma_trafionych_kursow_1-$liczba_1)/$liczba_1*100;
      }
      } */
    if ($typmax == '1') {
        $liczba_typowanych_1 += 1;
        if ($s_wynik == '1') {
            $liczba_trafionych_1 += 1;
            $liczba_trafien += 1;
            $suma_trafionych_kursow += $kurs1;
            $suma_trafionych_kursow_1 += $kurs1;
        }
        $yield_singli_1 = ($suma_trafionych_kursow_1 - $liczba_typowanych_1) / $liczba_typowanych_1 * 100;
    }
    if ($typmax == 'X') {
        $liczba_typowanych_x += 1;
        if ($s_wynik == 'X') {
            $liczba_trafionych_x += 1;
            $liczba_trafien += 1;
            $suma_trafionych_kursow += $kursx;
            $suma_trafionych_kursow_x += $kursx;
        }
        $yield_singli_x = ($suma_trafionych_kursow_x - $liczba_typowanych_x) / $liczba_typowanych_x * 100;
    }
    if ($typmax == '2') {
        $liczba_typowanych_2 += 1;
        if ($s_wynik == '2') {
            $liczba_trafionych_2 += 1;
            $liczba_trafien += 1;
            $suma_trafionych_kursow += $kurs2;
            $suma_trafionych_kursow_2 += $kurs2;
        }
        $yield_singli_2 = ($suma_trafionych_kursow_2 - $liczba_typowanych_2) / $liczba_typowanych_2 * 100;
    }
    $yield_singli = ($suma_trafionych_kursow - $liczba_meczow) / $liczba_meczow * 100;
    $trafienia_proc = number_format($liczba_trafien / $liczba_meczow * 100, 2);
//obliczanie Yield koniec			
//insert sila zespolow	


    if ($t_id_meczu != null) {
        $zapytanie_o_bramki = "select bramki_gospodarze, bramki_goscie, id_meczu from mecze 
	where id_meczu = $t_id_meczu";
        $pobrane_bramki = mysql_query($zapytanie_o_bramki) or die(mysql_error());
        $bramki_gospodarze = mysql_result($pobrane_bramki, 0, 'bramki_gospodarze');
        $bramki_goscie = mysql_result($pobrane_bramki, 0, 'bramki_goscie');
        '';
        //dodatkowa sekcja dodajaca wynik to tabeli sila START
        //if ($s_wynik == null )
        if (1 == 1) {
            if ($bramki_gospodarze > $bramki_goscie) {
                $wynik = '1';
            }
            if ($bramki_gospodarze == $bramki_goscie) {
                $wynik = 'X';
            }
            if ($bramki_gospodarze < $bramki_goscie) {
                $wynik = '2';
            }
            $update_wynik = " 
		update sila 
		set
		wynik = '$wynik'
		where id_terminu = $id_terminu limit 1";
            mysql_query($update_wynik) or die(mysql_error());
        }
        //dodatkowa sekcja dodajaca wynik to tabeli sila END
    }
///////////////	aktualizacja id__meczu zbiorczo
    //if ($wybrany_id_sezonu >= 71 and $kolejka < 19 and $id_meczu = '')

    /*
      else
      {
      $zapytanie_o_id_meczu = "select id_meczu from mecze
      where id_sezonu = '$wybrany_id_sezonu' and id_rozgrywek = '$wybrany_id_rozgrywek' and kolejka = '$kolejka'
      and id_gospodarza = '$id_gospodarza' and id_goscia = '$id_goscia'
      ";
      $pobrane_id_meczu = mysql_query($zapytanie_o_id_meczu) or die(mysql_error());
      if (mysql_num_rows ($pobrane_id_meczu)>0)
      {
      $m_id_meczu_cur = mysql_result($pobrane_id_meczu, 0,'id_meczu');
      $popraw_id_meczu =
      "update terminarz
      set id_meczu = $m_id_meczu_cur
      where id = $id_terminu
      limit 1 ";
      //mysql_query($popraw_id_meczu) or die(mysql_error());

      }
      }
     */
///////////////						koniec dodawnaia id_meczu
    //$bramki_goscie = mysql_result($pobrane_bramki, 0,'bramki_goscie');
    //$id_meczu = mysql_result($pobrane_bramki, 0,'id_meczu');
    //while ($rek1=mysql_fetch_array($pobrane_bramki)){$bramki_gospodarze = $rek1['bramki'];}



    $terminarz .= "
	<tr>";
    /* 	$terminarz .= "

      <td> $data_meczu </td>
      <td> $godzina </td>
      <td> $runda </td>
      <td > $kolejka </td>"; */
//start
    $terminarz .= "<td  > $data_meczu <br> $godzina </td>";
    //$kod_ligi = 
    $kod_ligi = kod_ligi($id_rozgrywek);
    if ($bramki_gospodarze == null) {
        $bramki_gospodarze = '?';
    }
    if ($bramki_goscie == null) {
        $bramki_goscie = '?';
    }

    $terminarz .= "<td  > $kod_ligi </td>";
    $terminarz .= "<td  > $kolejka </td>";
//end
    $terminarz .= "<td align= \"center\" > $gospodarze <br> - <br> $goscie </td>";
    $terminarz .= "<td h align= \"center\" > $bramki_gospodarze <br> : <br> $bramki_goscie </td>";


//	<td><input type=\"text\" name=\"typ1\" size=3 maxlength=5 value=\"$typ1\"></td>
//	<td><input type=\"text\" name=\"typx\" size=3 maxlength=5 value=\"$typx\"></td>
//	<td><input type=\"text\" name=\"typ2\" size=3 maxlength=5 value=\"$typ2\"></td>
//	$terminarz .="
//	<td> $goscie </td>";

    $terminarz .= "<td><table><tr>";
    if ($typmax == '1' and $wynik == '1') {
        $terminarz .= "<td bgcolor = \"greenyellow\" align = \"right\"><b> $typ1 [%]<b/></td></tr>";
    } else {
        if ($typmax == '1' and $wynik != '1') {
            $terminarz .= "<td  align = \"right\"><b> $typ1 [%]<b/></td></tr>";
        } else {
            $terminarz .= "<td  align = \"right\"> $typ1 [%]</td></tr>";
        }
    }

    if ($typmax == 'X' and $wynik == 'X') {
        $terminarz .= "<tr><td align = \"right\" bgcolor = \"greenyellow\"><b> $typx [%]<b/></td></tr>";
    } else {
        if ($typmax == 'X' and $wynik != 'X') {
            $terminarz .= "<tr><td  align = \"right\"><b> $typx [%]<b/></td></tr>";
        } else {
            $terminarz .= "<tr><td  align = \"right\"> $typx [%]</td></tr>";
        }
    }

    if ($typmax == '2' and $wynik == '2') {
        $terminarz .= "<tr><td align = \"right\" bgcolor = \"greenyellow\"><b> $typ2 [%]<b/></td>";
    } else {
        if ($typmax == '2' and $wynik != '2') {
            $terminarz .= "<tr><td  align = \"right\"><b> $typ2 [%]<b/></td>";
        } else {
            $terminarz .= "<tr><td  align = \"right\"> $typ2 [%]</td>";
        }
    }
    $terminarz .= "</tr></table></td>";
    //$terminarz .="
    //<td> $typx </td>
    //<td> $typ2 </td>
    $terminarz .= "<td><table><tr>";
//	$terminarz .="
//	<form method=\"post\" action=\"$_SERVER[PHP_SELF]\">";
    /* $terminarz .="
      <td><input type=\"text\" name=\"kurs1\" size=3 maxlength=5 value=\"$kurs1\"></td>
      <td><input type=\"text\" name=\"kursx\" size=3 maxlength=5 value=\"$kursx\"></td>
      <td><input type=\"text\" name=\"kurs2\" size=3 maxlength=5 value=\"$kurs2\"></td> */
    if ($typmax == '1' and $wynik == '1') {
        $terminarz .= "<td bgcolor = \"greenyellow\">$kurs1</tr>";
    } else {
        $terminarz .= "<td >$kurs1</tr>";
    }
    if ($typmax == 'X' and $wynik == 'X') {
        $terminarz .= "<tr><td bgcolor = \"greenyellow\">$kursx</td></tr>";
    } else {
        $terminarz .= "<tr><td >$kursx</td></tr>";
    }
    if ($typmax == '2' and $wynik == '2') {
        $terminarz .= "<tr><td bgcolor = \"greenyellow\">$kurs2</td>";
    } else {
        $terminarz .= "<tr><td >$kurs2</td>";
    }
    $terminarz .= "</tr></table></td>";

    if ($typmax == $wynik) {
        $terminarz .= "<td align=center bgcolor = \"greenyellow\"> $wynik </td>";
    } else {
        $terminarz .= "<td align=center> $wynik </td>";
    }

    $terminarz .= "</tr>";
}
$terminarz .= "</table>";
$czas = time();
$czas_logowania = date("Y-m-d G:i:s", $czas);
$data_logowania = date("Y-m-d", $czas);
$dodaj_log = "insert into statystyki_tabele
	(id, id_sezonu, id_rozgrywek, id_grupy, czas, data)
	values ('','$wybrany_id_sezonu','$wybrany_id_rozgrywek','$wybrany_id_grupy','$czas_logowania','$data_logowania')";


$wyswietlany_blok .= $terminarz;
if ($_SESSION[id_uzytkownika] > 0) {
    
} else {
    $wyswietlany_blok .= "
		<table width=700 align=center><tr><td>
		<br><b> <a href=\"logowanie.php\">Zaloguj sie</a> aby oddawac swoje typy </b>
		<br>
		<ul>
		<li> system automatycznie wylicza <b>orientacyjny Yield [%] gracza </b>
		<li><b> bez inwestowana realnej gotowki sprawdzisz czy jestes w stanie generowac zyski </b> czy tracisz 
		</ul>
		<br><b> Spos�b naliczania punkt�w w \"Lidze Typer�w\":</b>
		<ul>
		<li>za <b>bezb��dnie</b> wytypowany wynik - <b>10 pkt</b>,
		<li>je�eli gracz poprawnie wytypuje rezultat spotkania <b>(1,x,2)</b>, ale pomyli si� o pewna ilosc bramek, to w�wczas za ka�da bramk� r�nicy od 10 pkt <b>odejmuje si� 1 punkt</b>,
		<li>je�eli uczestnik nie trafi rezultatu spotkania (1,x,2), to za ka�da bramk� r�nicy odejmowany jest tak�e 1 pkt, ale <b>od 5 punkt�w</b>, mo�liwe jest zatem uzyskanie ujemnej liczby punkt�w w przypadku pomy�ki o du�a ilosc bramek,
		<li><b>przyk�ad 1:</b> wynik 3:2, typ 2:1, punkty 10 - 2 = <b>8 pkt</b>,
		<li><b>przyk�ad 2:</b> wynik 3:2, typ 0:1, punkty 5 - 4 = <b>1 pkt</b>,
		<li><b>przyk�ad 3:</b> wynik 1:4, typ 3:0, punkty 5 - 6 = <b>-1 pkt</b>
		</ul>
		</td></tr></table>
		";
}


echo $wyswietlany_blok;

//if(isset($_SESSION[zweryfikowany_login])) echo $wyswietlany_blok; 
?>
<?php // echo $wyswietlana_tabela_ligowa;   ?>
<?php //if(isset($_SESSION[zweryfikowany_login])) echo $terminarz;   ?>

<?php mysql_close();
?>
