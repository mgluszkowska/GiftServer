<?php
// is_numeric ($zmienna)  - funkcja php zwraca true je¿eli $zmienna jest numeryczna nawet przy typie string

function single_row_select($zapytanie,$pobierana_zmienna) //jednowierszowy select
{
$pobrane_zapytanie = mysql_query($zapytanie) or die(mysql_error());
$zmienna = mysql_result($pobrane_zapytanie,0,$pobierana_zmienna); //$pobierana_zmienna
return $zmienna;
}
function kod_ligi($id_rozgrywek) //jednowierszowy select
{
$zapytanie = "select kod_ligi from rozgrywki where id_rozgrywek = $id_rozgrywek";	
$pobrane_zapytanie = mysql_query($zapytanie) or die(mysql_error());
$kod_ligi = mysql_result($pobrane_zapytanie,0,'kod_ligi'); //$pobierana_zmienna
return $kod_ligi;
}
function show_text ($id_artykulu)
{
    $sql = "SELECT * FROM artykuly WHERE id = $id_artykulu and pokaz = 'tak' " ;
    $result = mysql_query($sql) or die (mysql_error());

	$data = mysql_result($result, 0,'data');
	$tytul = mysql_result($result, 0,'tytul');
	$zajawka = mysql_result($result, 0,'zajawka');
	$tresc = mysql_result($result, 0,'tresc');

	$wyswietlany_text .= $data."<br>";
	$wyswietlany_text .= "<b>".$tytul."</b><br><br>";
	$wyswietlany_text .= $zajawka."<br>";
	$wyswietlany_text .= $tresc."<br>";
return $wyswietlany_text;
}
function user_typ_stat_30($id_zakladu, $id_terminu, $id_rozgrywek, $id_sezonu, $id_typu, $id_uzytkownika)
{
$yield_before = null;
$bet_before = null;
$success_bet_before=null;
$zapytanie_yield_cur="
	SELECT 
	count(fwl_t.id) AS bet_before
	FROM fwl_typowania fwl_t
	JOIN terminarz t ON fwl_t.id_terminu = t.id
	WHERE zwrot is not null
	AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) < t.data_meczu
	AND CURDATE() >= t.data_meczu
	AND t.id_rozgrywek = $id_rozgrywek
	AND t.id_sezonu = $id_sezonu
	AND fwl_t.id_uzytkownika = $id_uzytkownika
	AND fwl_t.id_typu = $id_typu
	AND fwl_t.stawka > 0";
//(sum(zwrot)-sum(stawka)) /sum( stawka ) as yield_before
$yc = mysql_query($zapytanie_yield_cur) or die(mysql_error());
//$yield_before = mysql_result($yc, 0,'yield_before');
$bet_before = mysql_result($yc, 0,'bet_before');
$update_yc1 = "
update fwl_typowania set 
	bet_before = $bet_before
	where id = $id_zakladu
	limit 1";
$done = mysql_query($update_yc1) or die(mysql_error());
$komunikat.= '<br>bet_before updated';
$komunikat.= $bet_before;
if ($bet_before > 0)
{
$zapytanie_yield_cur="
	SELECT 
	(sum(zwrot)-sum(stawka)) /sum( stawka )*100 as yield_before
	FROM fwl_typowania fwl_t
	JOIN terminarz t ON fwl_t.id_terminu = t.id
	WHERE zwrot is not null
	AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) < t.data_meczu
	AND CURDATE() >= t.data_meczu
	AND t.id_rozgrywek = $id_rozgrywek
	AND t.id_sezonu = $id_sezonu
	AND fwl_t.id_uzytkownika = $id_uzytkownika
	AND fwl_t.id_typu = $id_typu
	AND fwl_t.stawka > 0";
//(sum(zwrot)-sum(stawka)) /sum( stawka ) as yield_before
$yc = mysql_query($zapytanie_yield_cur) or die(mysql_error());
$yield_before = mysql_result($yc, 0,'yield_before');
//$bet_before = mysql_result($yc, 0,'bet_before');
$update_yc1 = "
update fwl_typowania set 
	yield_before = $yield_before
	where id = $id_zakladu
	limit 1";
$done = mysql_query($update_yc1) or die(mysql_error());
$komunikat.= '<br>yield_before updated';
$komunikat.= $yield_before;
$zapytanie_yield_cur="
	SELECT 
	*
	FROM fwl_typowania fwl_t
	JOIN terminarz t ON fwl_t.id_terminu = t.id
	WHERE zwrot is not null
	AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) < t.data_meczu
	AND CURDATE() >= t.data_meczu
	AND t.id_rozgrywek = $id_rozgrywek
	AND t.id_sezonu = $id_sezonu
	AND fwl_t.id_uzytkownika = $id_uzytkownika
	AND fwl_t.id_typu = $id_typu
	AND fwl_t.stawka > 0
	and fwl_t.kurs_rozliczenia > 1";
$yc = mysql_query($zapytanie_yield_cur) or die(mysql_error());
$success_bet_before = mysql_num_rows ($yc);
$success_before_proc = number_format($success_bet_before/$bet_before*100,2);
$update_yc1 = "
update fwl_typowania set 
	success_bet_before = $success_bet_before,
	success_before_proc = $success_before_proc
	where id = $id_zakladu
	limit 1";
$done = mysql_query($update_yc1) or die(mysql_error());
$komunikat.= '<br>success_bet_before updated';
$komunikat.= $success_bet_before;

}
//insert konkretniego typowania $fwlt_id
return $komunikat;
}

function uzytkownik($id_uzytkownika)
{
$zapytanie = "
SELECT login FROM uzytkownicy WHERE id=$id_uzytkownika";
$pobrany = mysql_query($zapytanie) or die(mysql_error());
$nazwa = mysql_result($pobrany, 0,'login');
return $nazwa;
}
function typ($id_typu)
{
$zapytanie = "
SELECT nazwa_skrocona FROM fwl_typy WHERE id_typu = $id_typu";
$pobrany = mysql_query($zapytanie) or die(mysql_error());
$nazwa = mysql_result($pobrany, 0,'nazwa_skrocona');
return $nazwa;
}
function nazwa_klubu ($id_klubu)
{
$zapytanie_o_klub = "
SELECT nazwa_klubu FROM kluby WHERE id_klubu=$id_klubu";
$pobrany_klub = mysql_query($zapytanie_o_klub) or die(mysql_error());
$nazwa_klubu = mysql_result($pobrany_klub, 0,'nazwa_klubu');
return $nazwa_klubu;
}
function sezon ($id_sezonu)
{
$sql = "SELECT sezon FROM sezony WHERE id_sezonu = $id_sezonu";
$pobrany_sezon = mysql_query($sql) or die(mysql_error());
$sezon = mysql_result($pobrany_sezon, 0,'sezon');
return $sezon;
}
function rozgrywki ($id_rozgrywek)
{
$sql = "SELECT nazwa_rozgrywek FROM rozgrywki WHERE id_rozgrywek = $id_rozgrywek";
$pobrane_rozgrywki = mysql_query($sql) or die(mysql_error());
$rozgrywki = mysql_result($pobrane_rozgrywki, 0,'nazwa_rozgrywek');
return $rozgrywki;
}
function grupa ($id_grupy)
{
$grupa='';
	if($id_grupy>0)
	{
	$sql = "SELECT nazwa_grupy FROM grupy WHERE id_grupy = $id_grupy";
	$pobrana_grupa = mysql_query($sql) or die(mysql_error());
	$grupa = mysql_result($pobrana_grupa, 0,'nazwa_grupy');
	}
	else $grupa = '';
return $grupa;
}
function nazwa_rozgrywek ($id_rozgrywek, $id_sezonu)
{
$sql="select nazwa_rozgrywek from nazwy_rozgrywek where
id_sezonu = $id_sezonu	and
id_rozgrywek = $id_rozgrywek";
$pobrana_nazwa = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows ($pobrana_nazwa)>0)
{$rozgrywki = mysql_result($pobrana_nazwa, 0,'nazwa_rozgrywek');}
else 
{$sql="select nazwa_rozgrywek from rozgrywki where id_rozgrywek = $id_rozgrywek";
$pobrane_rozgrywki = mysql_query($sql) or die(mysql_error());
$rozgrywki = mysql_result($pobrane_rozgrywki, 0,'nazwa_rozgrywek');
}
return $rozgrywki;
}	
function nazwa_klubu_w_sezonie ($id_klubu, $id_sezonu)
{
$zapytanie_o_klub_w_sezonie = "
SELECT nazwa_klubu FROM udzial WHERE id_klubu=$id_klubu and id_sezonu = $id_sezonu";
$pobrany_klub = mysql_query($zapytanie_o_klub_w_sezonie) or die(mysql_error());
if (mysql_num_rows ($pobrany_klub)>0){
$nazwa_klubu = mysql_result($pobrany_klub, 0,'nazwa_klubu');}
else{
$zapytanie_o_klub = "
SELECT nazwa_klubu FROM kluby WHERE id_klubu=$id_klubu";
$pobrany_klub = mysql_query($zapytanie_o_klub) or die(mysql_error());
$nazwa_klubu = mysql_result($pobrany_klub, 0,'nazwa_klubu');
}
return $nazwa_klubu;
}
function ranking_bramki_ligowe ($id_zawodnika, $od_sezonu)
{

	$sql = " SELECT count( id_bramki ) as bramki , id_rozgrywek
	FROM mecze
	JOIN bramki ON bramki.id_meczu = mecze.id_meczu
	WHERE mecze.id_sezonu >= $od_sezonu
	AND mecze.id_rozgrywek
	IN ( 1, 2, 4, 5, 6 )
	AND bramki.id_zawodnika = $id_zawodnika
	GROUP BY id_rozgrywek";
	$pobrane_bramki = mysql_query($sql) or die(mysql_error());
	$ranking_bramki = 0;
	while ($rek = mysql_fetch_array($pobrane_bramki))
			{
			$bramki = $rek['bramki'];
			$id_rozgrywek = $rek['id_rozgrywek'];
			$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			$ranking_bramki += (integer)(100*$bramki/$wartosc_ligi);
			}
return $ranking_bramki;
}
function ranking_bramki_w_rozgrywkach ($id_zawodnika, $id_sezonu, $id_klubu, $id_rozgrywek)
{

	$sql = " SELECT *
	FROM mecze
	JOIN bramki ON bramki.id_meczu = mecze.id_meczu
	WHERE mecze.id_sezonu = $id_sezonu
	AND mecze.id_rozgrywek = $id_rozgrywek
	and bramki.bramka_dla_klubu = $id_klubu
	AND bramki.id_zawodnika = $id_zawodnika
	";
	$pobrane_bramki = 0;
	$ranking_bramki = 0;
	$pobrane_bramki = mysql_query($sql) or die(mysql_error());
	$bramki = mysql_num_rows ($pobrane_bramki);
	$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			if($id_rozgrywek == 17) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 18) {$wartosc_ligi = 6;}
			$ranking_bramki = (integer)(100*$bramki/$wartosc_ligi);
return $ranking_bramki;
}
function ranking_bramki_ligowe_w_sezonie ($id_zawodnika, $id_sezonu, $id_klubu)
{

	$sql = " SELECT count( id_bramki ) as bramki , id_rozgrywek
	FROM mecze
	JOIN bramki ON bramki.id_meczu = mecze.id_meczu
	WHERE mecze.id_sezonu = $id_sezonu
	AND mecze.id_rozgrywek
	IN ( 1, 2, 4, 5, 6 , 17, 18 )
	AND bramki.id_zawodnika = $id_zawodnika
	and bramki.bramka_dla_klubu = $id_klubu
	GROUP BY id_rozgrywek";
	$pobrane_bramki = mysql_query($sql) or die(mysql_error());
	$ranking_bramki = 0;
	while ($rek = mysql_fetch_array($pobrane_bramki))
			{
			$bramki = $rek['bramki'];
			$id_rozgrywek = $rek['id_rozgrywek'];
			$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			if($id_rozgrywek == 17) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 18) {$wartosc_ligi = 6;}
			$ranking_bramki += (integer)(100*$bramki/$wartosc_ligi);
			}
return $ranking_bramki;
}
function ranking_zawodnika ($id_zawodnika, $id_sezonu, $id_klubu, $id_rozgrywek)
{
//analiza z-r-p
		$zapytanie_o_mecze = "SELECT * FROM mecze, sklady
		WHERE mecze.id_sezonu = $id_sezonu and
		mecze.id_rozgrywek = $id_rozgrywek and
		sklady.id_zawodnika = $id_zawodnika and
		sklady.id_meczu = mecze.id_meczu and
		sklady.id_klubu = $id_klubu";
		$lista_meczow = mysql_query($zapytanie_o_mecze) or die(mysql_error());
		$czas_punkty = 0;
		$ranking = 0;
			while ($rek = mysql_fetch_array($lista_meczow))
			{
			$id_meczu = $rek['id_meczu'];
			$id_gospodarza = $rek['id_gospodarza'];
			$id_goscia = $rek['id_goscia'];
			$bramki_gospodarze = $rek['bramki_gospodarze'];
			$bramki_goscie = $rek['bramki_goscie'];
			$klub_zawodnika = $rek['id_klubu'];
			$czas_na_boisku = $rek['czas_na_boisku'];
			$id_rozgrywek = $rek['id_rozgrywek'];
			$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			if($id_rozgrywek == 17) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 18) {$wartosc_ligi = 6;}
				if ($id_gospodarza == $klub_zawodnika)
				{
					if($bramki_gospodarze > $bramki_goscie){$z+=1;$czas_punkty += 3*$czas_na_boisku/$wartosc_ligi;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;$czas_punkty += 1*$czas_na_boisku/$wartosc_ligi;}
					if($bramki_gospodarze < $bramki_goscie){$p+=1;$czas_punkty += 0.3*$czas_na_boisku/$wartosc_ligi;}
				} else
				{
					if($bramki_gospodarze > $bramki_goscie){$p+=1;$czas_punkty += 0.3*$czas_na_boisku/$wartosc_ligi;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;$czas_punkty += 1*$czas_na_boisku/$wartosc_ligi;}
					if($bramki_gospodarze < $bramki_goscie){$z+=1;$czas_punkty += 3*$czas_na_boisku/$wartosc_ligi;}
				}
			}
			$czas_punkty = 	(integer) $czas_punkty;
			$ranking_bramki = ranking_bramki_w_rozgrywkach($id_zawodnika, $id_sezonu, $id_klubu, $id_rozgrywek);
			$ranking = $czas_punkty + $ranking_bramki;

//koniec analizy z-r-p
return $ranking;
}
function ranking_zespolu ($id_sezonu, $id_klubu, $id_rozgrywek)
{
//analiza z-r-p
		$zapytanie_o_mecze = "SELECT * FROM mecze
		WHERE mecze.id_sezonu = $id_sezonu and
		mecze.id_rozgrywek = $id_rozgrywek and
		(mecze.id_gospodarza = $id_klubu or mecze.id_goscia = $id_klubu) and
		mecze.walkower !=1";
		$lista_meczow = mysql_query($zapytanie_o_mecze) or die(mysql_error());
		$mecze = mysql_num_rows ($lista_meczow);
		$czas_punkty = 0;
		$ranking = 0;
		$bramki = 0;
		if ($id_rozgrywek == 18) {$czas_meczu = 80;} else {$czas_meczu = 90;}
			while ($rek = mysql_fetch_array($lista_meczow))
			{
			$id_meczu = $rek['id_meczu'];
			$id_gospodarza = $rek['id_gospodarza'];
			$id_goscia = $rek['id_goscia'];
			$bramki_gospodarze = $rek['bramki_gospodarze'];
			$bramki_goscie = $rek['bramki_goscie'];
			$wartosc_ligi = $id_rozgrywek;
			if($id_rozgrywek == 5) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 6) {$wartosc_ligi = 9;}
			if($id_rozgrywek == 17) {$wartosc_ligi = 6;}
			if($id_rozgrywek == 18) {$wartosc_ligi = 6;}
				if ($id_gospodarza == $id_klubu)
				{
					$bramki += $bramki_gospodarze*100/11/$wartosc_ligi;
					if($bramki_gospodarze > $bramki_goscie){$z+=1;$czas_punkty += 3*$czas_meczu/$wartosc_ligi;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;$czas_punkty += 1*$czas_meczu/$wartosc_ligi;}
					if($bramki_gospodarze < $bramki_goscie){$p+=1;$czas_punkty += 0.3*$czas_meczu/$wartosc_ligi;}
				} else 
				{
					$bramki += $bramki_goscie*100/11/$wartosc_ligi;
					if($bramki_gospodarze > $bramki_goscie){$p+=1;$czas_punkty += 0.3*$czas_meczu/$wartosc_ligi;}
					if($bramki_gospodarze == $bramki_goscie){$r+=1;$czas_punkty += 1*$czas_meczu/$wartosc_ligi;}
					if($bramki_gospodarze < $bramki_goscie){$z+=1;$czas_punkty += 3*$czas_meczu/$wartosc_ligi;}
				}
			}
			//$czas_punkty = 	(integer) $czas_punkty;
			$czas = $mecze*$czas_meczu; 
			$ranking = $czas_punkty + $bramki;
			$ranking_zespolu = $ranking/$czas;

//koniec analizy z-r-p
return $ranking_zespolu;
//return $czas_punkty;
}
function wyswietlanie_tabeli($tabele_dostepne_wybrany_id){
	$zapytanie_o_parametry = "select * from tabele_dostepne where id = $tabele_dostepne_wybrany_id";
	$pobrana_lista_parametrow = mysql_query($zapytanie_o_parametry) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_parametrow)) {
		$_POST[wybrany_id_sezonu] = $rek['id_sezonu'];
		$_POST[wybrany_id_rozgrywek] = $rek['id_rozgrywek'];
		$_POST[wybrany_id_grupy] = $rek['id_grupy'];
		$awans = $rek['awans'];
		$baraz_a = $rek['baraz_a'];
		$baraz_s = $rek['baraz_s'];
		$spadek = $rek['spadek'];
		$redaktor = $rek['redaktor'];
		$frekwencja = $rek['frekwencja'];
		if ($redaktor != null){$wys_red = 'autor: '.$redaktor;} else $wys_red = '';
		
	}

	$zapytanie_o_sezon = "select sezon from sezony where id_sezonu = $_POST[wybrany_id_sezonu]";
	$pobrana_lista_sezonow = mysql_query($zapytanie_o_sezon) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_sezonow)) {$sezon = $rek['sezon'];}
/*	$zapytanie_o_rozgrywki = "select nazwa_rozgrywek from rozgrywki where id_rozgrywek = $_POST[wybrany_id_rozgrywek]";
	$pobrana_lista_rozgrywek = mysql_query($zapytanie_o_rozgrywki) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_rozgrywek)) {$rozgrywki = $rek['nazwa_rozgrywek'];}
*/
	$rozgrywki = nazwa_rozgrywek ($_POST[wybrany_id_rozgrywek], $_POST[wybrany_id_sezonu]);
	if ($_POST[wybrany_id_grupy]==0){
	$wyswietlany_blok = "<h3 align =\"center\">Sezon $sezon | $rozgrywki | $wys_red</h3>";}
	else {
		$zapytanie_o_grupe = "select nazwa_grupy from grupy where id_grupy = $_POST[wybrany_id_grupy]";
		$pobrana_grupa = mysql_query($zapytanie_o_grupe) or die(mysql_error());
		while ($rek = mysql_fetch_array($pobrana_grupa)){$nazwa_grupy = $rek['nazwa_grupy'];}
		$wyswietlany_blok = "<h3 align =\"center\">Sezon $sezon | $rozgrywki | $nazwa_grupy | $wys_red</h3>";}
	$nazwa = "tabela_";
	$nazwa .= "$_POST[wybrany_id_sezonu]_";
	$nazwa .= "$_POST[wybrany_id_rozgrywek]_";
	$nazwa .= "$_POST[wybrany_id_grupy]";
	$zapytanie_tabela = "select id_klubu, klub, m, pkt, z, r ,p, CONCAT( bz,' - ',bs ) bramki,
	zd, rd, pd, CONCAT( bzd, ' - ',bsd ) bd, zw, rw, pw, CONCAT( bzw, ' - ',bsw ) bw, widzow_d, widzow_w,
	zproc, rproc, pproc, kara, b_lr, b_m, b_pkt, b_z, b_r, b_p, CONCAT( b_bz, ' - ',b_bs ) b_b, b_bzw
	from $nazwa";
if ($_POST[wybrany_id_sezonu]>=41 and $_POST[wybrany_id_sezonu]<=44)
{
	$zapytanie_tabela = "select id_klubu, klub, m, pkt, z, zplus3, r ,p, pminus1, CONCAT( bz,' - ',bs ) bramki,
	zd, zdplus3, rd, pd, pdminus1, CONCAT( bzd, ' - ',bsd ) bd, zw, zwplus3, rw, pw, pwminus1, CONCAT( bzw, ' - ',bsw ) bw, widzow_d, widzow_w,	zproc, rproc, pproc, kara, b_lr, b_m, b_pkt, b_z, b_r, b_p, CONCAT( b_bz, ' - ',b_bs ) b_b, b_bzw
	from $nazwa";
}
	$pobrana_tabela = mysql_query($zapytanie_tabela) or die(mysql_error());
	$wyswietlany_blok .= "
<table border=\"0\" align =\"center\">
<tr>
<td bgcolor=\"gainsboro\" colspan=\"2\"></td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"6\"><font size=1 face=Arial>razem</td>";
if ($frekwencja =='n'){$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"4\"><font size=1 face=Arial>dom</td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"4\"><font size=1 face=Arial>wyjazd</td>";}
else{$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"5\"><font size=1 face=Arial>dom</td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"5\"><font size=1 face=Arial>wyjazd</td>";}
	$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\"></td>";
if($_POST[wybrany_id_sezonu] >= 48)
{
$wyswietlany_blok .="
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"7\"><font size=1 face=Arial>mecze bezposrednie</td>";
}
$wyswietlany_blok .="
</tr>
<tr>
<td bgcolor=\"blue\"></td>
<td bgcolor=\"blue\" align =\"left\"><font size=1 color=\"white\" face=Arial>klub</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>m</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>pkt</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>frek</font></td>";}
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>frek</font></td>";}
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>k</font></td>";
if($_POST[wybrany_id_sezonu] >= 48)
{
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>m</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>pkt</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bw</font></td>";
}
$wyswietlany_blok .= "</tr>";
	$miejsce = 0;
	while ($tabela = mysql_fetch_array ($pobrana_tabela)) {
	$id_klubu=$tabela['id_klubu'];
	$klub=$tabela['klub'];
	$m=$tabela['m'];
	$pkt=$tabela['pkt'];
	$z=$tabela['z'];
	$r=$tabela['r'];
	$p=$tabela['p'];
	$bramki=$tabela['bramki'];
	$zd=$tabela['zd'];
	$rd=$tabela['rd'];
	$pd=$tabela['pd'];
	$bd=$tabela['bd'];
	$zw=$tabela['zw'];
	$rw=$tabela['rw'];
	$pw=$tabela['pw'];
	$bw=$tabela['bw'];
	$widzow_d=$tabela['widzow_d'];
	$widzow_w=$tabela['widzow_w'];
	$zproc=$tabela['zproc'];
	$rproc=$tabela['rproc'];
	$pproc=$tabela['pproc'];
	$kara=$tabela['kara'];
//
	if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44)
	{
	$zplus3=$tabela['zplus3'];
	$pminus1=$tabela['pminus1'];
	$zdplus3=$tabela['zdplus3'];
	$pdminus1=$tabela['pdminus1'];
	$zwplus3=$tabela['zwplus3'];
	$pwminus1=$tabela['pwminus1'];
	}
//
	$b_lr=$tabela['b_lr'];
	$b_m=$tabela['b_m'];
	$b_pkt=$tabela['b_pkt'];
	$b_z=$tabela['b_z'];
	$b_r=$tabela['b_r'];
	$b_p=$tabela['b_p'];
	$b_b=$tabela['b_b'];
	$b_bzw=$tabela['b_bzw'];
	$miejsce += 1; 
//wstawianie linii poziomej po 8 mmiejscu w tabeli w sezonach gdzie by³ podzia³ na grupe spadkowa i mistrzowska	
	if ($miejsce == 9 and ($tabele_dostepne_wybrany_id == 166 or $tabele_dostepne_wybrany_id == 172 or $tabele_dostepne_wybrany_id == 186
	or $tabele_dostepne_wybrany_id == 198))
	{ 
	$wyswietlany_blok .= "<tr><td bgcolor=\"black\" align = \"center\"colspan=\"27\"><font size=1 face=Arial> </td></tr>";
	}	
	$wyswietlany_blok .= "<tr>";
	$bgcolor = 'black';
	$color = 'white';
if ($miejsce <= $baraz_a) { $bgcolor = 'yellow'; $color = 'black'; if ($miejsce <= $awans) {$bgcolor = 'green';}}
if (($miejsce >= $baraz_s)and ($baraz_s!=0)) { $bgcolor = 'yellow'; $color = 'black';}
if (($miejsce >= $spadek) and ($spadek!=0)) {$bgcolor = 'red';}
	$wyswietlany_blok .= "
<td bgcolor=$bgcolor align =\"center\"><font size=1 color=$color face=Arial>$miejsce</td>";
//		if ($id_klubu ==47 or $id_klubu ==584 or $id_klubu ==314 or $id_klubu ==486)
//		{
//	$wyswietlany_blok .= "
//<td bgcolor=\"black\" align =\"left\"><font size=1 color=\"white\" face=Arial><b>$klub</b></font></td>";
//	}else{
//	$wyswietlany_blok .= "
//<td bgcolor=\"blue\" align =\"left\"><font size=1 color=\"white\" face=Arial><b>$klub</b></font></td>";
//	}
	$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"left\"><font size=1 color=\"white\" face=Arial><b>$klub</b></font></td>";
	$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$m</td>
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$pkt</b></td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$r</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bramki</b></td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$zd";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zdplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$rd</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$pd";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pdminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bd</b></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial>$widzow_d</td>";}
$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$zw";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zwplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$rw</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$pw";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pwminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bw</b></td>";

if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial>$widzow_w</td>";}
if ($kara!=0)
{$wyswietlany_blok .="<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$kara</td>";} else 
{$wyswietlany_blok .="<td bgcolor=\"gainsboro\"></td>";}
if($_POST[wybrany_id_sezonu] >= 48)
{
	if ($b_lr > 0) {$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_m</td>
	<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_pkt</b></td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_z</td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_r</td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_p</td>
	<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_b</b></td>";
	if ($b_lr == 1) {$wyswietlany_blok .="
	<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_bzw</b></td>";}
	else{$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\"></td>";}
	}else {$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>";}
}
$wyswietlany_blok .="</tr>";

	}
//wyznaczanie sredniej frekwencji ligowej w danym sezonie
if ($frekwencja =='t')
{	$zapytanie_o_frekwencje = "SELECT  sum(liczba_widzow) AS widzow_razem, count(id_meczu) AS liczba_meczow_razem
                FROM mecze WHERE
		(`id_rozgrywek`= $_POST[wybrany_id_rozgrywek])
		and (id_grupy = $_POST[wybrany_id_grupy])
		and (bez_publiki = 0)
		and (id_sezonu = $_POST[wybrany_id_sezonu])";
	$pobrana_frekwencja = mysql_query($zapytanie_o_frekwencje) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_frekwencja)) {
		$widzow_razem = $rek['widzow_razem'];
		$liczba_meczow_razem = $rek['liczba_meczow_razem'];
		$srednia_frekwencja = $widzow_razem/$liczba_meczow_razem;
	settype($srednia_frekwencja, 'integer');
	}
$wyswietlany_blok .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$srednia_frekwencja</b></td></tr>";
}
$wyswietlany_blok .= "</table><hr color=blue>";
return $wyswietlany_blok;
}
function opis_rankingu_zawodnikow ( )
{
$wyswietlanie_opisu .="
		
		
		<br><b> Sposób obliczania punktów do rankingu zawodników:</b>
		<ul>
		<li>Punkty za ka¿dy mecz naliczane sa proporcjonalnie do <b>ilosci minut</b> spêdzonych przez zawodnika na boisku.
		<li>W przypadku zwyciêstwa dru¿yny liczba minut z tego spotkania mno¿ona jest razy 3, w przypadku pora¿ki mno¿ona jest razy 0,3.
		<li>Punkty dzielone sa tak¿e przez wskaznik <b>poziomu rozgrywkowego</b>. Nowa I liga wskaznik 2, nowa II liga - 4, nowa III liga - 6, nowa IV liga - 9, Centralna Liga Juniorów - 6.
		<li>Dodatkowo punktowane sa <b>bramki</b> zdobyte przez zawodnika.
		<li>Liczba bramek mno¿ona jest przez 100 i dzielona przez wskaznik poziomu rozgrywkowego.
		</ul>
		<br>
		<br><b> Przyk³ad:</b>
		<ul>
		<li>Zawodnik rozegra³ 60 minut w spotkaniu - bazowa liczba punktów 60;
		<li>Spotkanie by³o wygrane - liczba punktów mno¿ona jest x 3; czyli razem 180 punktów;
		<li>Zawodnik strzeli³ gola - dodatkowe 100 punktów; czyli razem 280 punktów;
		<li>Spotkanie by³o rozegrane w nowej III lidze, zatem punkty dzielimy przez 6; po zaokragleniu 46 punktów; 
		</ul>
		
		";
return $wyswietlanie_opisu;
}
function wyswietlanie_tabeli_krzyzowej($tabele_dostepne_wybrany_id){
	$zapytanie_o_parametry = "select * from tabele_dostepne where id = $tabele_dostepne_wybrany_id";
	$pobrana_lista_parametrow = mysql_query($zapytanie_o_parametry) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_parametrow)) {
		$_POST[wybrany_id_sezonu] = $rek['id_sezonu'];
		$_POST[wybrany_id_rozgrywek] = $rek['id_rozgrywek'];
		$_POST[wybrany_id_grupy] = $rek['id_grupy'];
		$awans = $rek['awans'];
		$baraz_a = $rek['baraz_a'];
		$baraz_s = $rek['baraz_s'];
		$spadek = $rek['spadek'];
		$frekwencja = $rek['frekwencja'];
	}

	$zapytanie_o_sezon = "select sezon from sezony where id_sezonu = $_POST[wybrany_id_sezonu]";
	$pobrana_lista_sezonow = mysql_query($zapytanie_o_sezon) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_sezonow)) {$sezon = $rek['sezon'];}
/*	$zapytanie_o_rozgrywki = "select nazwa_rozgrywek from rozgrywki where id_rozgrywek = $_POST[wybrany_id_rozgrywek]";
	$pobrana_lista_rozgrywek = mysql_query($zapytanie_o_rozgrywki) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_lista_rozgrywek)) {$rozgrywki = $rek['nazwa_rozgrywek'];}
*/
	$rozgrywki = nazwa_rozgrywek ($_POST[wybrany_id_rozgrywek], $_POST[wybrany_id_sezonu]);
	if ($_POST[wybrany_id_grupy]==0){
	$wyswietlany_blok = "<h3 align =\"center\">Sezon $sezon | $rozgrywki </h3>";}
	else {
		$zapytanie_o_grupe = "select nazwa_grupy from grupy where id_grupy = $_POST[wybrany_id_grupy]";
		$pobrana_grupa = mysql_query($zapytanie_o_grupe) or die(mysql_error());
		while ($rek = mysql_fetch_array($pobrana_grupa)){$nazwa_grupy = $rek['nazwa_grupy'];}
		$wyswietlany_blok = "<h3 align =\"center\">Sezon $sezon | $rozgrywki | $nazwa_grupy</h3>";}
	$nazwa = "tabela_";
	$nazwa .= "$_POST[wybrany_id_sezonu]_";
	$nazwa .= "$_POST[wybrany_id_rozgrywek]_";
	$nazwa .= "$_POST[wybrany_id_grupy]";
	$zapytanie_tabela = "select id_klubu, klub, m, pkt, z, r ,p, CONCAT( bz,' - ',bs ) bramki,
	zd, rd, pd, CONCAT( bzd, ' - ',bsd ) bd, zw, rw, pw, CONCAT( bzw, ' - ',bsw ) bw, widzow_d, widzow_w,
	zproc, rproc, pproc, kara, b_lr, b_m, b_pkt, b_z, b_r, b_p, CONCAT( b_bz, ' - ',b_bs ) b_b, b_bzw
	from $nazwa";
	$zapytanie_num_rows = "select count(*) as num_rows from $nazwa";
	$pobrana_num_rows = mysql_query($zapytanie_num_rows) or die(mysql_error());
	$num_rows = mysql_result($pobrana_num_rows, 0,'num_rows');
	
	
	if ($_POST[wybrany_id_sezonu]>=41 and $_POST[wybrany_id_sezonu]<=44)
{
	$zapytanie_tabela = "select id_klubu, klub, m, pkt, z, zplus3, r ,p, pminus1, CONCAT( bz,' - ',bs ) bramki,
	zd, zdplus3, rd, pd, pdminus1, CONCAT( bzd, ' - ',bsd ) bd, zw, zwplus3, rw, pw, pwminus1, CONCAT( bzw, ' - ',bsw ) bw, widzow_d, widzow_w,	zproc, rproc, pproc, kara, b_lr, b_m, b_pkt, b_z, b_r, b_p, CONCAT( b_bz, ' - ',b_bs ) b_b, b_bzw
	from $nazwa";
}
	$pobrana_tabela = mysql_query($zapytanie_tabela) or die(mysql_error());
	$wyswietlany_blok .= "
<table border=\"0\" align =\"center\">
<tr>
<td bgcolor=\"gainsboro\" colspan=\"2\"></td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"6\"><font size=1 face=Arial>razem</td>";
if ($frekwencja =='n'){$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"4\"><font size=1 face=Arial>dom</td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"4\"><font size=1 face=Arial>wyjazd</td>";}
else{$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"5\"><font size=1 face=Arial>dom</td>
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"5\"><font size=1 face=Arial>wyjazd</td>";}
	$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\"></td>";
if($_POST[wybrany_id_sezonu] >= 48)
{
$wyswietlany_blok .="
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"7\"><font size=1 face=Arial>mecze bezposrednie</td>";
}
$wyswietlany_blok .="
<td bgcolor=\"gainsboro\" align = \"center\"colspan=\"$num_rows\"><font size=1 face=Arial>tabela krzyzowa</td>";
$wyswietlany_blok .="
</tr>
<tr>
<td bgcolor=\"blue\"></td>
<td bgcolor=\"blue\" align =\"left\"><font size=1 color=\"white\" face=Arial>klub</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>m</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>pkt</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>frek</font></td>";}
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (+3)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .=" (-1)";}
$wyswietlany_blok .="
</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>frek</font></td>";}
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>k</font></td>";
if($_POST[wybrany_id_sezonu] >= 48)
{
$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>m</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>pkt</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>z</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>r</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>p</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bramki</font></td>
<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>bw</font></td>";
}
//$wyswietlany_blok .= "num_rows  = $num_rows";
$licznik = 0;
while ( $licznik < $num_rows) {
$licznik +=1;
$wyswietlany_blok .= "<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial>$licznik</font></td>";
}

$wyswietlany_blok .= "</tr>";
	$miejsce = 0;
	while ($tabela = mysql_fetch_array ($pobrana_tabela)) {
	$id_klubu=$tabela['id_klubu'];
	$klub=$tabela['klub'];
	$m=$tabela['m'];
	$pkt=$tabela['pkt'];
	$z=$tabela['z'];
	$r=$tabela['r'];
	$p=$tabela['p'];
	$bramki=$tabela['bramki'];
	$zd=$tabela['zd'];
	$rd=$tabela['rd'];
	$pd=$tabela['pd'];
	$bd=$tabela['bd'];
	$zw=$tabela['zw'];
	$rw=$tabela['rw'];
	$pw=$tabela['pw'];
	$bw=$tabela['bw'];
	$widzow_d=$tabela['widzow_d'];
	$widzow_w=$tabela['widzow_w'];
	$zproc=$tabela['zproc'];
	$rproc=$tabela['rproc'];
	$pproc=$tabela['pproc'];
	$kara=$tabela['kara'];
	$kolejnosc = $tabela['b_kolejnosc'];
	
//
	if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44)
	{
	$zplus3=$tabela['zplus3'];
	$pminus1=$tabela['pminus1'];
	$zdplus3=$tabela['zdplus3'];
	$pdminus1=$tabela['pdminus1'];
	$zwplus3=$tabela['zwplus3'];
	$pwminus1=$tabela['pwminus1'];
	}
//
	$b_lr=$tabela['b_lr'];
	$b_m=$tabela['b_m'];
	$b_pkt=$tabela['b_pkt'];
	$b_z=$tabela['b_z'];
	$b_r=$tabela['b_r'];
	$b_p=$tabela['b_p'];
	$b_b=$tabela['b_b'];
	$b_bzw=$tabela['b_bzw'];
	
	$miejsce += 1; 
//wstawianie linii poziomej po 8 mmiejscu w tabeli w sezonach gdzie by³ podzia³ na grupe spadkowa i mistrzowska	
	if ($miejsce == 9 and ($tabele_dostepne_wybrany_id == 166 or $tabele_dostepne_wybrany_id == 172))
	{ 
	$wyswietlany_blok .= "<tr><td bgcolor=\"black\" align = \"center\"colspan=\"27\"><font size=1 face=Arial> </td></tr>";
	}	
	$wyswietlany_blok .= "<tr>";
	$bgcolor = 'black';
	$color = 'white';
if ($miejsce <= $baraz_a) { $bgcolor = 'yellow'; $color = 'black'; if ($miejsce <= $awans) {$bgcolor = 'green';}}
if (($miejsce >= $baraz_s)and ($baraz_s!=0)) { $bgcolor = 'yellow'; $color = 'black';}
if (($miejsce >= $spadek) and ($spadek!=0)) {$bgcolor = 'red';}
	$wyswietlany_blok .= "
<td bgcolor=$bgcolor align =\"center\"><font size=1 color=$color face=Arial>$miejsce</td>";
		if ($id_klubu ==47 or $id_klubu ==584 or $id_klubu ==314 or $id_klubu ==486){
	$wyswietlany_blok .= "
<td bgcolor=\"black\" align =\"left\"><font size=1 color=\"white\" face=Arial><b>$klub</b></font></td>";
	}else{
	$wyswietlany_blok .= "
<td bgcolor=\"blue\" align =\"left\"><font size=1 color=\"white\" face=Arial><b>$klub</b></font></td>";
	}
	$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$m</td>
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$pkt</b></td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$z";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$r</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$p";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bramki</b></td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$zd";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zdplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$rd</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$pd";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pdminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bd</b></td>";
if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial>$widzow_d</td>";}
$wyswietlany_blok .= "
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$zw";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($zwplus3)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$rw</td>
<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$pw";
if ($_POST[wybrany_id_sezonu] >= 41 and $_POST[wybrany_id_sezonu] <= 44){$wyswietlany_blok .= " ($pwminus1)";}
	$wyswietlany_blok .= "
</td><td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$bw</b></td>";

if ($frekwencja =='t'){$wyswietlany_blok .= "
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial>$widzow_w</td>";}
if ($kara!=0)
{$wyswietlany_blok .="<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$kara</td>";} else 
{$wyswietlany_blok .="<td bgcolor=\"gainsboro\"></td>";}
if($_POST[wybrany_id_sezonu] >= 48)
{
	if ($b_lr > 0) {$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_m</td>
	<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_pkt</b></td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_z</td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_r</td>
	<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 face=Arial>$b_p</td>
	<td bgcolor=\"blue\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_b</b></td>";
	if ($b_lr == 1) {$wyswietlany_blok .="
	<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$b_bzw</b></td>";}
	else{$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\"></td>";}
	}else {$wyswietlany_blok .="
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>
	<td bgcolor=\"gainsboro\"></td>";}
}
$licznik = 0;
while ( $licznik < $num_rows) {
$licznik +=1;
$bramki_gospodarze = null;
$bramki = null;
//$miejsce  - miejsce w tabeli 
	//$zapytanie_id_rywala = "select id_klubu as id_rywala from $nazwa where b_kolejnosc = $licznik";
	$zapytanie_bramki = " select CONCAT( bramki_gospodarze,' - ',bramki_goscie ) as bramki ,bramki_gospodarze, bramki_goscie from mecze
	where id_gospodarza = $id_klubu and id_goscia = (select id_klubu as id_rywala from $nazwa where b_kolejnosc = $licznik)
	and id_sezonu = $_POST[wybrany_id_sezonu]
	and id_rozgrywek = $_POST[wybrany_id_rozgrywek]";
	$pobrane_bramki = mysql_query($zapytanie_bramki) or die(mysql_error());
	if (mysql_num_rows ($pobrane_bramki)>0)

	{$bramki = mysql_result($pobrane_bramki,0,'bramki');
	$bramki_gospodarze = mysql_result($pobrane_bramki,0,'bramki_gospodarze');
	$bramki_goscie = mysql_result($pobrane_bramki,0,'bramki_goscie');
	}
	
	else
	if ($miejsce == $licznik) $bramki = 'X';
		else
		$bramki = '-';

	if ($bramki_gospodarze == null and $miejsce == $licznik)
	{
	$wyswietlany_blok .= "<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
	}else 
		if ($bramki_gospodarze == null)
	{
	if ($miejsce % 2 == 0)
		$wyswietlany_blok .= "<td bgcolor=\"white\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
		else
		$wyswietlany_blok .= "<td bgcolor=\"whitesmoke\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
	}else 
		if ($bramki_gospodarze > $bramki_goscie)
	{
	$wyswietlany_blok .= "<td bgcolor=\"yellowgreen\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
	}else 
	if ($bramki_gospodarze < $bramki_goscie)
	{
	$wyswietlany_blok .= "<td bgcolor=\"red\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
	}
	else if ($bramki_gospodarze == $bramki_goscie)
	{
	$wyswietlany_blok .= "<td bgcolor=\"yellow\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
	}
	else
	$wyswietlany_blok .= "<td bgcolor=\"gainsboro\" align =\"center\"><font size=1 color=\"black\" face=Arial>$bramki</font></td>";
}
$wyswietlany_blok .="</tr>";

	}
//wyznaczanie sredniej frekwencji ligowej w danym sezonie
if ($frekwencja =='t')
{	$zapytanie_o_frekwencje = "SELECT  sum(liczba_widzow) AS widzow_razem, count(id_meczu) AS liczba_meczow_razem
                FROM mecze WHERE
		(`id_rozgrywek`= $_POST[wybrany_id_rozgrywek])
		and (id_grupy = $_POST[wybrany_id_grupy])
		and (bez_publiki = 0)
		and (id_sezonu = $_POST[wybrany_id_sezonu])";
	$pobrana_frekwencja = mysql_query($zapytanie_o_frekwencje) or die(mysql_error());
	while ($rek = mysql_fetch_array($pobrana_frekwencja)) {
		$widzow_razem = $rek['widzow_razem'];
		$liczba_meczow_razem = $rek['liczba_meczow_razem'];
		$srednia_frekwencja = $widzow_razem/$liczba_meczow_razem;
	settype($srednia_frekwencja, 'integer');
	}
$wyswietlany_blok .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
<td bgcolor=\"black\" align =\"center\"><font size=1 color=\"white\" face=Arial><b>$srednia_frekwencja</b></td></tr>";
}
$wyswietlany_blok .= "</table><hr color=blue>";
return $wyswietlany_blok;
}

function bilans_update($id_terminu, $flaga_test, $wsp, $wsp_10, $wsp_2, $ilosc_1, $ilosc_x, $ilosc_2) //$wsp to reczne korekty do wspolczynnikow 
{
$sql = "select id_sezonu, id_rozgrywek, id_grupy, id_gospodarza, id_goscia, data_meczu, id_meczu, kolejka from terminarz  where id = '$id_terminu' ";
$pobrany = mysql_query ($sql) or die (mysql_error());
while ($rek = mysql_fetch_array ($pobrany)){
$id_biezacego_sezonu = $rek['id_sezonu'];
$id_rozgrywek = $rek['id_rozgrywek'];
$id_grupy = $rek['id_grupy'];
$id_gospodarza = $rek['id_gospodarza'];
$id_goscia = $rek['id_goscia'];


$data_meczu = $rek['data_meczu'];
$id_meczu = $rek['id_meczu'];
$kolejka = $rek['kolejka'];
//pobieranie konfiguracji sezonu , wagi czynników, limit ostatnich spotkan
$limit = 5;
//pobieranie konfiguracji sezonu , wagi czynników, limit ostatnich spotkan
}
//poczatek starej funkcji bilans
global $wyswietlany_blok;
global $waga_korekty_2;
//wyzerowanie zmiennych prze wejœciem do while'a
$wyswietlaj_punktacje = 1;		
global $suma; $suma = 0;
global $suma_10; $suma_10 = 0;
global $korekta_b_zd; $korekta_b_zd = 0;
global $korekta_b_rd; $korekta_b_rd = 0;
global $korekta_b_pd; $korekta_b_pd = 0;
global $korekta_10_zd; $korekta_10_zd = 0;
global $korekta_10_rd; $korekta_10_rd = 0; 
global $korekta_10_pd; $korekta_10_pd = 0;
//global $waga_korekty_10; $waga_korekty_10 = 0;
global $waga_korekty_10; $waga_korekty_10 = 0; //MG201607014
global $waga_korekty_10_d; $waga_korekty_10_d = 0; //MG201607014
global $waga_korekty_10_w; $waga_korekty_10_w = 0; //MG201607014
global $blok;
global $gospodarz; $gospodarz = nazwa_klubu ($id_gospodarza);
global $gosc; $gosc = nazwa_klubu ($id_goscia);
$blok  .= "'$gospodarz'";
$blok  .= " - '$gosc'";		
		$zapytanie_mecze_domowe="select 
			CONCAT( bramki_gospodarze,' : ',bramki_goscie) bramki_dom,walkower, bramki_gospodarze, bramki_goscie
			from mecze WHERE
			mecze.id_gospodarza = $id_gospodarza and
			mecze.id_rozgrywek=$id_rozgrywek
			and data_meczu < '$data_meczu'
			and walkower !=1
			and id_sezonu >= $id_biezacego_sezonu-1
			order by data_meczu desc
			limit $limit";
			$pobrane_mecze_domowe = mysql_query($zapytanie_mecze_domowe) or die(mysql_error());
			//if (mysql_num_rows($pobrane_mecze_domowe) < $limit){$wyswietlany_blok .= "brak $limit meczów domowych dru¿yny gospodarzy";}
			$licznik_m = 0; //licznik pobranych meczow
			$suma_10 = 0;
			while ($rek = mysql_fetch_array($pobrane_mecze_domowe))
			{
			$licznik_m +=1;
			$bramki_dom = $rek['bramki_dom'];
			$walkower = $rek['walkower'];
			$bramki_gospodarze = $rek['bramki_gospodarze'];
			$bramki_goscie = $rek['bramki_goscie'];
			$waga_meczu = 12 - 2*$licznik_m;
			$suma_10 += $waga_meczu;
			
//dane do prognozy
			if ($bramki_gospodarze>$bramki_goscie)	{$korekta_10_zd +=$waga_meczu;}
			if ($bramki_gospodarze==$bramki_goscie) {$korekta_10_rd +=$waga_meczu;}
			if ($bramki_gospodarze<$bramki_goscie)	{$korekta_10_pd +=$waga_meczu;}
			}
			$waga_korekty_10_d = $suma_10/6; //MG21060714 - suma 
			if ($suma_10 > 0) {
			$korekta_10_zd = $korekta_10_zd*10000/$suma_10;
			$korekta_10_rd = $korekta_10_rd*10000/$suma_10;
			$korekta_10_pd = $korekta_10_pd*10000/$suma_10;}
//koniec ost 10 meczów domowych gospodarza
//ostatnie 10 ligowych meczów wyjazdowych goœcia
		/*$zapytanie_10_meczow_wyjazdowych="select 
			CONCAT( bramki_gospodarze,' : ',bramki_goscie) bramki_dom,walkower, bramki_gospodarze, bramki_goscie
			from mecze WHERE
			mecze.id_goscia = $id_goscia and
			(mecze.id_rozgrywek=1 or mecze.id_rozgrywek=2 or mecze.id_rozgrywek=4 or mecze.id_rozgrywek=5 or mecze.id_rozgrywek=6)
			and data_meczu < '$data_meczu'
			and walkower !=1
			order by data_meczu desc
			limit 5"; */ //MG20160714 nowa wersja eliminuje mecze beniaminków w ni¿szej lidze, a pozostawia pozosta³e
		$zapytanie_10_meczow_wyjazdowych="select 
			CONCAT( bramki_gospodarze,' : ',bramki_goscie) bramki_dom,walkower, bramki_gospodarze, bramki_goscie
			from mecze WHERE
			mecze.id_goscia = $id_goscia and
			mecze.id_rozgrywek=$id_rozgrywek
			and data_meczu < '$data_meczu'
			and id_sezonu >= $id_biezacego_sezonu-1
			and walkower !=1
			order by data_meczu desc
			limit $limit";
			$pobrane_10_meczow_wyjazdowych = mysql_query($zapytanie_10_meczow_wyjazdowych) or die(mysql_error());
			//if (mysql_num_rows($pobrane_10_meczow_wyjazdowych) < $limit){$wyswietlany_blok .= "brak 10 meczów wyjazdowych dru¿yny gosci";}
			$licznik_m = 0;
			$suma_10 = 0;
			while ($rek = mysql_fetch_array($pobrane_10_meczow_wyjazdowych))
			{
			$licznik_m +=1;
			$bramki_dom = $rek['bramki_dom'];
			$walkower = $rek['walkower'];
			$bramki_gospodarze = $rek['bramki_gospodarze'];
			$bramki_goscie = $rek['bramki_goscie'];
			$waga_meczu = 12 - 2*$licznik_m;
			$suma_10 += $waga_meczu;
//dane do prognozy
			if ($bramki_gospodarze>$bramki_goscie)	{$korekta_10_pw +=$waga_meczu;}
			if ($bramki_gospodarze==$bramki_goscie) {$korekta_10_rw +=$waga_meczu;}
			if ($bramki_gospodarze<$bramki_goscie)	{$korekta_10_zw +=$waga_meczu;}
			}
			$waga_korekty_10_w = $suma_10/6; //MG21060714
			if ($suma_10 > 0) {
			$korekta_10_zw = $korekta_10_zw*10000/$suma_10;
			$korekta_10_rw = $korekta_10_rw*10000/$suma_10;
			$korekta_10_pw = $korekta_10_pw*10000/$suma_10;}
			/*$korekta_10_zd = ($korekta_10_zd + $korekta_10_pw)/2;
			$korekta_10_rd = ($korekta_10_rd + $korekta_10_rw)/2;
			$korekta_10_pd = ($korekta_10_pd + $korekta_10_zw)/2;*/
			$korekta_10_zd = ($korekta_10_zd*$waga_korekty_10_d + $korekta_10_pw*$waga_korekty_10_w)/($waga_korekty_10_d+$waga_korekty_10_w); //MG20160714
			$korekta_10_rd = ($korekta_10_rd*$waga_korekty_10_d + $korekta_10_rw*$waga_korekty_10_w)/($waga_korekty_10_d+$waga_korekty_10_w);
			$korekta_10_pd = ($korekta_10_pd*$waga_korekty_10_d + $korekta_10_zw*$waga_korekty_10_w)/($waga_korekty_10_d+$waga_korekty_10_w);
			$waga_korekty_10 = ($waga_korekty_10_d + $waga_korekty_10_w)/2; //MG20160714
//koniec ost 10 meczów wyjazdowych goœcia
//sezony rywalizacji	
	$zapytanie_o_sezony_rywalizacji = "
	(SELECT distinct sezony.id_sezonu, sezon, id_rozgrywek
	FROM sezony, mecze WHERE 
	mecze.id_sezonu = sezony.id_sezonu and
	mecze.id_gospodarza = $id_gospodarza and mecze.id_goscia = $id_goscia
	and mecze.id_rozgrywek <> 3
	and mecze.id_sezonu>0)
	UNION
	(SELECT distinct sezony.id_sezonu, sezon, id_rozgrywek
	FROM sezony, mecze WHERE 
	mecze.id_sezonu = sezony.id_sezonu and
	mecze.id_gospodarza = $id_goscia and mecze.id_goscia = $id_gospodarza
	and mecze.id_rozgrywek <> 3
	and mecze.id_sezonu>0)
	order by id_sezonu desc";
	$pobrana_lista_sezonow = mysql_query($zapytanie_o_sezony_rywalizacji) or die(mysql_error());
	if (mysql_num_rows($pobrana_lista_sezonow) < 1){$wyswietlaj_punktacje = 0;}
		if ($godzina == '0:00'){$godzina="";}
		/*<h3 align=\"center\">
		<a href=\"ostatnie_10_meczow.php?id_klubu=$id_gospodarza\">
		$gospodarz</a> vs. 
		<a href=\"ostatnie_10_meczow.php?id_klubu=$id_goscia\">
		$gosc</a>, $data_meczu $godzina</h3>
				<h3 align=\"center\">
		$gospodarz vs. 
		$gosc, $data_meczu $godzina</h3>*/
/*		$wyswietlany_blok .= "
<table align=center width=800><tr><td bgcolor=whitesmoke>
<h3 align=\"center\">
		<a href=\"ostatnie_10_meczow.php?id_klubu=$id_gospodarza\">
		$gospodarz</a> vs. 
		<a href=\"ostatnie_10_meczow.php?id_klubu=$id_goscia\">
		$gosc</a>, $data_meczu $godzina</h3>
		<table align=\"center\" width=600>
		<th width=100px bgcolor=\"blue\"><font size=3 color=\"white\">Sezon</th>
		<th width=180px bgcolor=\"blue\"><font size=3 color=\"white\">Rozgrywki</th>
		<th width=70px bgcolor=\"blue\"><font size=3 color=\"white\">Dom</th>
		<th width=70px bgcolor=\"blue\"><font size=3 color=\"white\">Wyjazd</th>
		";
	*/	
		while ($rek = mysql_fetch_array($pobrana_lista_sezonow))
		{
		$id_sezonu = $rek['id_sezonu'];
		$id_rozgrywek = $rek['id_rozgrywek'];
		$sezon = $rek['sezon'];
	/*$zapytanie_o_rozgrywki = "select nazwa_rozgrywek from rozgrywki where id_rozgrywek = $id_rozgrywek";
	$pobrana_lista_rozgrywek = mysql_query($zapytanie_o_rozgrywki) or die(mysql_error());
		while ($rek = mysql_fetch_array($pobrana_lista_rozgrywek))
		{
		$rozgrywki = $rek['nazwa_rozgrywek'];
		}*/
		$rozgrywki = nazwa_rozgrywek ($id_rozgrywek, $id_sezonu);
	/*	$wyswietlany_blok .= "<tr>
		<td bgcolor=black align=center><font color=\"white\"><b>$sezon</b></font></td>
		<td bgcolor=gainsboro align=center>$rozgrywki</td><td bgcolor=whitesmoke align=center>";*/
//mecze domowe - ka¿dy sezon w osobnym zapytaniu		//MG20160729 dodany warunek data_meczu < '$data_meczu' and i dopuszczono pobieranie poprzedeniego meczu zespolów z tego samego sezonu PLAY -off
		$zapytanie_o_mecze_domowe="select 
			CONCAT( bramki_gospodarze,' : ',bramki_goscie) bramki_dom,walkower, bramki_gospodarze, bramki_goscie
			from mecze
			where mecze.id_sezonu = $id_sezonu and
			data_meczu < '$data_meczu' and
			mecze.id_gospodarza = $id_gospodarza and
			mecze.id_goscia = $id_goscia
			and mecze.id_rozgrywek <> 3";
			$pobrane_mecze_domowe = mysql_query($zapytanie_o_mecze_domowe) or die(mysql_error());
			if (mysql_num_rows($pobrane_mecze_domowe) < 1){$wyswietlaj_punktacje = 0;}
			$licznik_m = 0;
			while ($rek = mysql_fetch_array($pobrane_mecze_domowe))
			{
			$licznik_m +=1;
			$bramki_dom = $rek['bramki_dom'];
			$walkower = $rek['walkower'];
			$bramki_gospodarze = $rek['bramki_gospodarze'];
			$bramki_goscie = $rek['bramki_goscie'];
			
				if($id_biezacego_sezonu>=$id_sezonu and $id_sezonu>($id_biezacego_sezonu - 11) and $walkower!=1) //> zmienione na >= Mg20160729
			 	{
				$waga_meczu = $id_sezonu - $id_biezacego_sezonu + 11;
				$suma += $waga_meczu;
//dane do prognozy
				if ($bramki_gospodarze>$bramki_goscie and $walkower!=1)
					{$korekta_b_zd +=$waga_meczu;}
				if ($bramki_gospodarze==$bramki_goscie and $walkower!=1)
					{$korekta_b_rd +=$waga_meczu;}
				if ($bramki_gospodarze<$bramki_goscie and $walkower!=1)
					{$korekta_b_pd +=$waga_meczu;}
				}	
				if (mysql_num_rows($pobrane_mecze_domowe) < 1)
				{
				$wyswietlaj_punktacje = 0;
				//$wyswietlany_blok .= "<b></b>";
				}
				else
				{
				//$wyswietlany_blok .= "<b>";
					if($id_sezonu == $id_biezacego_sezonu)
					{
					$bramki_terminarz_gospodarze = $bramki_gospodarze;
					$bramki_terminarz_goscie = $bramki_goscie;
					//$wyswietlany_blok.="<font color=blue>";
					}
				//obsluga wiêkszej iloœci meczów dru¿yn w tym samym sezonie
/*				if ($licznik_m ==1){$wyswietlany_blok.="$bramki_dom</b>";}
				if ($licznik_m >1){$wyswietlany_blok.=", $bramki_dom </b>";}
				if ($walkower == 1){$wyswietlany_blok .= "(w)";}*/
				}
			}
		//$wyswietlany_blok .= "</td>";

//mecze wyjazdowe - bilans nie jest uwzglêdniany
		//$wyswietlany_blok .= "<td bgcolor=whitesmoke align=center>";
		$zapytanie_o_mecze_wyjazdowe="select 
			CONCAT( bramki_goscie,' : ',bramki_gospodarze) bramki_wyjazd,walkower
			from mecze
			where mecze.id_sezonu = $id_sezonu and
			data_meczu < '$data_meczu' and
			mecze.id_gospodarza = $id_goscia and
			mecze.id_goscia = $id_gospodarza 
			and mecze.id_rozgrywek <> 3";
			$pobrane_mecze_wyjazdowe = mysql_query($zapytanie_o_mecze_wyjazdowe) or die(mysql_error());
//			if (mysql_num_rows($pobrane_mecze_wyjazdowe) < 1){$wyswietlaj_punktacje = 0;}
			$licznik_m = 0;
			while ($rek = mysql_fetch_array($pobrane_mecze_wyjazdowe))
			{
			$licznik_m += 1;
			$bramki_wyjazd = $rek['bramki_wyjazd'];
			$walkower = $rek['walkower'];

/*				if (mysql_num_rows($pobrane_mecze_wyjazdowe) < 1)
				{$wyswietlany_blok .= "<b></b>";
				}
				else
				{
				if ($licznik_m == 1){$wyswietlany_blok .= "<b>$bramki_wyjazd</b>";}
				if ($licznik_m > 1){$wyswietlany_blok .= "<b>, $bramki_wyjazd</b>";}
				if ($walkower == 1){$wyswietlany_blok .= "(w)";}
				}*/
			}
//		$wyswietlany_blok .= "</td>";		
//		$wyswietlany_blok .= "</tr>";
		}
//		$wyswietlany_blok .= "
//		</table>
//</td><td bgcolor=whitesmoke> ";

//dla zalogowanych
//koniec bloku dla zalogowanych

//		$wyswietlany_blok .= "
		
//</td></tr></table>";
		if($suma>0)
		{
		$korekta_b_zd = $korekta_b_zd/$suma*10000;
		$korekta_b_rd = $korekta_b_rd/$suma*10000;
		$korekta_b_pd = $korekta_b_pd/$suma*10000;
		}
//ustalanie wagi korekty wg bilansu spotkañ bezpoœrednich

//if($suma>20){$waga_korekty_2 = 2;}else{$waga_korekty_2 = $suma/10;} MG20160217 zmniejszenie wagi o po³owê
if($suma>20){$waga_korekty_2 = 1;}else{$waga_korekty_2 = $suma/20;}		

//return compact('wyswietlany_blok','korekta_b_zd','korekta_b_rd','korekta_b_pd');

//koniec starej funkcji bilans

//reszta obliczen poczatek
$t1 = 3920;$tx = 2954;$t2 = 3125; //ustawienia dla POL1 72

$t1 = $ilosc_1*100; //MG20160726 przekazywane z prompta
$tx = $ilosc_x*100;
$t2 = $ilosc_2*100;
//extract(bilans_klubu_dom($id_gospodarza, $nazwa_tabeli));

//$wyswietlany_blok .="<h4 align=center>korekta_zd =  $korekta_zd</h4>";
//$wyswietlany_blok .="<h4 align=center>korekta_rd =  $korekta_rd</h4>";
//$wyswietlany_blok .="<h4 align=center>korekta_pd =  $korekta_pd</h4>";
//extract(bilans_klubu_wyjazd($id_goscia, $nazwa_tabeli));

//$wyswietlany_blok .="<h4 align=center>korekta_zw =  $korekta_zw</h4>";
//$wyswietlany_blok .="<h4 align=center>korekta_rw =  $korekta_rw</h4>";
//$wyswietlany_blok .="<h4 align=center>korekta_pw =  $korekta_pw</h4>";
$korekta_t1 = ($korekta_10_zd + $korekta_10_pw)/2;
$korekta_tx = ($korekta_10_rd + $korekta_10_rw)/2;
$korekta_t2 = ($korekta_10_pd + $korekta_10_zw)/2;
//$waga_korekty_10 = 2.5;// dziesiêæ ostatnich spotkañ //MG20160218
if ($kolejka < 11) {$waga_korekty_10 = $waga_korekty_10 * ($kolejka*0.08+0.12);}  // w pierwszej kolejce ma byæ 20% tego wskaznika, w jedensatej kolejce ma byæ 100% wagi tego wskaŸnika
else {$waga_korekty_10 = 5.0;}
//$waga_korekty_10 = 5.0;// dziesiêæ ostatnich spotkañ //MG20160714 waga wyliczana w funkcji bilans, beniaminkowie poczatkowo zero, po 5 meczach domowych i wyjazdowych wartosc wagi = 5 
//w sezonie 08/09 by³o 4.0
//pocz¹tek sezonu 09/10 przestawiam na 2.0
$waga_korekty = 1.5;//standardowy rozk³ad wyników z-r-p
// punkt wyjœcia  by³o 0.5
//w sezonie 08/09 by³o 0.8
// $waga_koekty_2 korekta wynikaj¹ca ze spotkañ bezpoœrednich wyliczana (w zakresie 0 - 2 ) zale¿y od iloœci staræ i jak dawno temu to by³o

$waga_korekty = $waga_korekty*$wsp;
$waga_korekty_10 = $waga_korekty_10*$wsp_10;
$waga_korekty_2 = $waga_korekty_2*$wsp_2;


$t1 = ($waga_korekty*$t1 + $waga_korekty_10*$korekta_10_zd + $waga_korekty_2*$korekta_b_zd)/
($waga_korekty_2+$waga_korekty_10+$waga_korekty);
$tx = ($waga_korekty*$tx + $waga_korekty_10*$korekta_10_rd + $waga_korekty_2*$korekta_b_rd)/
($waga_korekty_2+$waga_korekty_10+$waga_korekty);
$t2 = ($waga_korekty*$t2 + $waga_korekty_10*$korekta_10_pd + $waga_korekty_2*$korekta_b_pd)/
($waga_korekty_2+$waga_korekty_10+$waga_korekty);
//$pproc=10000*$p/$liczba_gier;$pproc = (integer) $pproc; $pproc/=100;
$t1 = (integer) $t1; $typ1 = $t1/100;
$tx = (integer) $tx; $typx = $tx/100;
$t2 = (integer) $t2; $typ2 = $t2/100;
$blok .= "korekta_10_zd = $korekta_10_zd korekta_b_zd = $korekta_b_zd" ;
$blok .="Typ( format 1,X,2): ($typ1 %, $typx %, $typ2 %) ";
$blok .=" waga korekty =$waga_korekty > waga_korekty_10 = $waga_korekty_10 > waga korekty _2 = $waga_korekty_2  <br>";

//reszta obliczen koniec
//$typ1 = 30.10;
//$typx = 35.40;
//$typ2 = 34.60;
//zapis do bazy wartosci testowych

if ($flaga_test == 'test') {
$sql = "update terminarz 
		set 
	typ1_test = $typ1,
	typx_test = $typx,
	typ2_test = $typ2
	where id = $id_terminu
	limit 1";
	mysql_query ($sql) or die (mysql_error());
	$blok .= "sekcja update test wykonana";
	}
if ($flaga_test == 'prd') {
$sql = "update terminarz 
		set 
	typ1 = $typ1,
	typx = $typx,
	typ2 = $typ2
	where id = $id_terminu
	limit 1";
	mysql_query ($sql) or die (mysql_error());
	$blok .= "sekcja update test wykonana";
	}
	return $blok;}

?>

