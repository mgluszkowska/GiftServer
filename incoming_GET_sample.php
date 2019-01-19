<?php

include ("polaczenie.php");

$temp = $_GET[temperatura];
$temp_wew = $_GET[temperatura_wew];
$device_id = 0;
$device_name = $_GET[device_name];
if ($device_name == 'Temp_out') {$device_id = 1; $note = $device_name;}
if ($device_name == 'Temp_inside') {$device_id = 2; $note = $device_name;}
if ($device_name == 'Temp_m4a') {$device_id = 4; $note = $device_name;}
if ($device_name == 'Temp_m1') {$device_id = 11; $note = $device_name;}
if ($device_name == 'Temp_m2') {$device_id = 12; $note = $device_name;}
if ($device_name == 'Temp_m3') {$device_id = 13; $note = $device_name;}
if ($device_name == 'Temp_m4') {$device_id = 14; $note = $device_name;}
if ($device_name == 'Temp_m5') {$device_id = 15; $note = $device_name;}
if ($device_name == 'Temp_m6') {$device_id = 16; $note = $device_name;}


if ($temp < 99) 
{		$dodaj_temperature = "
INSERT INTO domoticz_temperatury (
`id` ,
`temperatura` ,
`id_czujnika` ,
`czas`,
`note`
)
VALUES (
NULL ,
'$temp',
'$device_id',
NULL,
'$note'
)";
		//mysql_query($dodaj_temerature);
		$wyswietlany_blok .= mysql_query($dodaj_temperature) or die(mysql_error());
		$wyswietlany_blok .=" dadano temperature '$_GET[temperatura]' na urzadzeniu '$_GET[device_name]' ";

}		
else
if ($temp_wew < 99) 
{		$dodaj_temperature = "
INSERT INTO domoticz_temperatury (
`id` ,
`temperatura` ,
`id_czujnika` ,
`czas`,
`note`
)
VALUES (
NULL ,
'$temp_wew',
'2',
NULL,
'$note'
)";
		//mysql_query($dodaj_temerature);
		$wyswietlany_blok .= mysql_query($dodaj_temperature) or die(mysql_error());
		$wyswietlany_blok .=" dadano temperatura wewnetrzna '$_GET[temperatura_wew]' na urzadzeniu '$_GET[device_name]' ";

}		
else 		
		$wyswietlany_blok .=" <BR>NIE dadano temperatur metoda GET zmiennej temperatura o wartosci '$_GET[temperatura]'";


?>
<HTML>
<head>
<title> tempetatura </title>
<META http-equiv=content-type content="text/html; charset=iso-8859-2">
</head>
<BODY>
<body leftmargin="50"></body>
<body bgcolor="white", link="red", alink="purpure"></body>
<?php echo $wyswietlany_blok; ?>
<?php mysql_close(); ?>
</BODY>
</HTML>
