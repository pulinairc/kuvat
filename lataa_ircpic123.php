<?php
//urlgrab.pl:
$logitiedosto = "/var/www/html/urllog.log";
$file = $logitiedosto;

$fp = fopen($file, 'r');

if ($fp) {
$lines = array();

while (($line = fgets($fp)) !== false) {
$lines[] = $line;
while (count($lines) > 50)
array_shift($lines);
}

$reverse = array_reverse($lines);

foreach ($reverse as $line) {

$rivi = $line;
$poistettavat = array('Haamu');
$tilalle = array('');
$hieno = str_replace($poistettavat, $tilalle, $rivi);

$aikanyt = time();
//näin saataisiin epoch, mutta sitä ei tässä tapauksessa tarvita:
//$aikakunpastettu = mktime($tunnitt, $minuutitt, 00, $kuukausi, $paiva, $vuosi);

$epochi = explode(" ", $line);
$aikakunpastettu = $epochi[0];
$irkkaaja = $epochi[1];
$kanava = $epochi[2];
$linkki = $epochi[3];
//kuvalinkkien päätteet:
$kuvat = array('jpg','png','gif','jpeg');

if (preg_match("/Haamu/", $line) or preg_match("/root/", $line) or preg_match("/Meteorologi/", $line) or preg_match("/kummitus/", $line)) { echo ''; } else {
//validoidaan vähäsen:
//poistetaan turha tyhjä väli linkin lopusta:
$linkki = substr($linkki,0,-1);
//katsotaan linkin kolme viimeistä merkkiä:
$urlin_tiedostomuoto = substr($linkki, -3);
//jos on kuva niin tehdään jännittäviä juttuja!
if (in_array($urlin_tiedostomuoto, $kuvat)) { 
//paikallisen kuvan sijainti:
$poistettavat = array('/',':',' ');
$kuvatiedosto = str_replace($poistettavat,'',$linkki);
$paikallinen_tiedosto = '/var/www/html/ircpics/'.$kuvatiedosto.'';
//jos tiedostoa ei ole cache-kansiossa:
if (!file_exists($paikallinen_tiedosto)) {
//haetaan se sinne...
copy($linkki, $paikallinen_tiedosto);
}
} else {
} 
}
}

fclose($fp);
}


?>