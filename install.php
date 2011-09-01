<html>
<head>
<title>Phitherek_' s SMPBNS - MOD: Comments - Instalacja</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
if($_POST['setprefix'] == 1) {
$prefixfile=fopen("smpbns_prefix.php","w");
flock($prefixfile, LOCK_EX);
fputs($prefixfile, '<?php'."\n");
fputs($prefixfile, '$prefix="'.$_POST['prefix'].'";'."\n");
fputs($prefixfile, '?>');
flock($prefixfile, LOCK_UN);
fclose($prefixfile);
if(file_exists("smpbns_prefix.php")) {
echo("Prefiks został zapisany pomyślnie!<br />");	
} else {
echo("Nie udało się zapisać pliku z prefiksem! Sprawdź uprawnienia katalogu i spróbuj ponownie!<br />");	
}
}
if(file_exists("smpbns_prefix.php")) {
include("smpbns_prefix.php");
$prefixexists = true;
} else {
$prefixexists = false;	
}
if($prefixexists == true) {
session_start();
if (!isset($_SESSION[$prefix.'started'])) {
session_regenerate_id();
$_SESSION[$prefix.'started'] = true;
}
if($_POST['beginpass']=="BtW24oPx") { 
	$_SESSION[$prefix.'login'] = 1;
	session_regenerate_id();
}
if($_SESSION[$prefix.'login'] == 1) {
$step = $_POST['go'];
if($step == 1) {
?>
<h1>Ustawianie MySQL</h1><br />
Czy chcesz utworzyć nową bazę danych MySQL?<br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="2" />
<input type="submit" value="Tak" />
</form>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="3" />
<input type="submit" value="Nie" />
</form>

<?php
} else if($step == 2) {
?>
<h1>Ustawianie MySQL</h1><br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
Adres serwera MySQL: <input type="text" name="serek" value="localhost" /><br />
Nazwa użytkownika MySQL: <input type="text" name="dbuser" value="root" /><br />
Hasło MySQL: <input type="password" name="dbpass" /><br />
Nazwa nowej bazy danych: <input type="text" name="dbname" value="smpbns" /><br />
<input type="hidden" name="go" value="3" />
<input type="hidden" name="newdb" value="1" />
<input type="submit" value="Wykonaj" />
</form>
<?php
} else if($step == 3) {
if($_POST['newdb'] == 1) {
echo("<h1>Ustawianie MySQL</h1><br />");
$baza=mysql_connect($_POST['serek'],$_POST['dbuser'],$_POST['dbpass']) 
or die("Połączenie z serwerem MySQL nieudane!");
echo("Połączono z serwerem MySQL!<br />");
$zapytanie=mysql_query("CREATE DATABASE ".$_POST['dbname']);
if($zapytanie == 1) {
echo("Nowa baza danych utworzona poprawnie!<br />");
} else {
?>
Błąd podczas tworzenia nowej bazy danych!<br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="2" />
<input type="submit" value="Powrót" />
</form>
<?php
echo("Zamykam połączenie z serwerem MySQL...<br />");
mysql_close($baza);
die();
}
echo("Zamykam połączenie z serwerem MySQL...<br />");
mysql_close($baza);
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
Adres serwera MySQL: <input type="text" name="serek" value="<?php echo $_POST['serek']; ?>" /><br />
Nazwa użytkownika MySQL: <input type="text" name="dbuser" value="<?php echo $_POST['dbuser']; ?>" /><br />
Hasło MySQL: <input type="password" name="dbpass" /><br />
Nazwa bazy danych: <input type="text" name="dbname" value="<?php echo $_POST['dbname']; ?>" /><br />
Prefiks tabeli: <input type="text" name="dbprefix" value="smpbns_" /><br />
<input type="checkbox" name="parsedefault" value="1" checked /> Domyślnie włącz SMPBNS Code Parser<br />
<input type="checkbox" name="slmlock" value="1" /> Użyj SLMlock (zablokuj dostęp niezarejestrowanym użytkownikom)<br />
<input type="checkbox" name="slmreglock" value="1" /> Rejestracja dozwolona tylko dla administratorów<br />
<input type="checkbox" name="myonly" value="1" /> Tryb MyOnly - administrator może moderować tylko swoje wpisy<br />
<input type="hidden" name="go" value="4" />
<input type="submit" value="Wykonaj i zapisz" />
</form>
<?php
} else {
?>
<h1>Ustawianie MySQL</h1><br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
Adres serwera MySQL: <input type="text" name="serek" value="localhost" /><br />
Nazwa użytkownika MySQL: <input type="text" name="dbuser" value="root" /><br />
Hasło MySQL: <input type="password" name="dbpass" /><br />
Nazwa bazy danych: <input type="text" name="dbname" value="smpbns" /><br />
Prefiks tabeli: <input type="text" name="dbprefix" value="smpbns_" /><br />
<input type="checkbox" name="parsedefault" value="1" checked /> Domyślnie włącz SMPBNS Code Parser<br />
<input type="checkbox" name="slmlock" value="1" /> Użyj SLMlock (zablokuj dostęp niezarejestrowanym użytkownikom)<br />
<input type="checkbox" name="slmreglock" value="1" /> Rejestracja dozwolona tylko dla administratorów<br />
<input type="checkbox" name="myonly" value="1" /> Tryb MyOnly - administrator może moderować tylko swoje wpisy<br />
<input type="hidden" name="go" value="4" />
<input type="submit" value="Wykonaj i zapisz" />
</form>

<?php
}
} else if($step==4) {
echo("<h1>Ustawianie MySQL i zapisywanie ustawień</h1><br /><br />");
$baza=mysql_connect($_POST['serek'],$_POST['dbuser'],$_POST['dbpass'])
or die("Połączenie z serwerem MySQL nieudane!");
echo("Połączono z serwerem MySQL!<br />");
mysql_select_db($_POST['dbname']);
$zapytanie=mysql_query("CREATE TABLE ".$_POST['dbprefix']."news_main (id INT NOT NULL AUTO_INCREMENT, title VARCHAR(100), content TEXT, added TIMESTAMP, parse BOOLEAN NOT NULL, user VARCHAR(100), umod VARCHAR(100), PRIMARY KEY(id))");
if($zapytanie == 1) {
echo("Tabela dla postów została utworzona poprawnie!<br />");
$zapytanie=mysql_query("CREATE TABLE ".$_POST['dbprefix']."news_comments (id INT NOT NULL AUTO_INCREMENT, content TEXT, added TIMESTAMP, user VARCHAR(100), postid INT, PRIMARY KEY(id))");
if($zapytanie == 1) {
echo("Tabela dla komentarzy została utworzona poprawnie!<br />");
} else {
?>
Błąd! Tabela dla komentarzy nie została utworzona! Ustawienia nie zostaną zapisane!<br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="3" />
<input type="submit" value="Powrót" />
</form>
<?php
$fail=1;
}
} else {
?>
Błąd! Tabela dla postów nie została utworzona! Ustawienia nie zostaną zapisane!<br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="3" />
<input type="submit" value="Powrót" />
</form>
<?php
$fail=1;
}
if($fail!=1) {
$ustawienia=fopen("smpbns_settings.php","w");
flock($ustawienia,LOCK_EX);
fputs($ustawienia,'<?php '."\n");
fputs($ustawienia,'$serek="'.$_POST['serek'].'"'.";\n");
fputs($ustawienia,'$dbuser="'.$_POST['dbuser'].'"'.";\n");
fputs($ustawienia,'$dbpass="'.$_POST['dbpass'].'"'.";\n");
fputs($ustawienia,'$dbname="'.$_POST['dbname'].'"'.";\n");
fputs($ustawienia,'$dbprefix="'.$_POST['dbprefix'].'"'.";\n");
if($_POST['parsedefault'] == 1) {
fputs($ustawienia,'$parsedefault='.$_POST['parsedefault'].";\n");
} else {
fputs($ustawienia,'$parsedefault=0'.";\n");	
}
if($_POST['slmlock'] == 1) {
fputs($ustawienia,'$slmlock='.$_POST['slmlock'].";\n");
} else {
fputs($ustawienia,'$slmlock=0'.";\n");	
}
if($_POST['slmreglock'] == 1) {
fputs($ustawienia,'$slmreglock='.$_POST['slmreglock'].";\n");
} else {
fputs($ustawienia,'$slmreglock=0'.";\n");	
}
if($_POST['myonly'] == 1) {
fputs($ustawienia,'$myonly='.$_POST['myonly'].";\n");
} else {
fputs($ustawienia,'$myonly=0'.";\n");	
}
fputs($ustawienia,'?>');
flock($ustawienia,LOCK_UN);
fclose($ustawienia);
if(file_exists("smpbns_settings.php")) {
echo("Ustawienia zostały zapisane!<br />");
} else {
echo("Nie można było zapisać ustawień! Sprawdź, czy katalog z plikami systemu SMPBNS ma uprawnienia 777 (lub rwxrwxrwx), jeżeli nie, to zmień je, a następnie usuń tabelę (prefix)_news_main (i bazę danych) z serwera MySQL, zakończ sesję przeglądarki, a następnie uruchom ten plik install.php ponownie!<br />");
}
echo("<br /> Koniec instalacji! WAŻNE: Skasuj ten plik install.php z serwera, aby nikt nie mógł zmienić Twoich ustawień! Aby korzystać z SUDS z modem SLMlock, musisz zainstalować SLM. W tym celu uruchom <a href=".'"slm_install.php"'.">slm_install.php</a>, jeżeli jeszcze tego nie zrobiłeś/aś.");
}
}
} else {
echo("Aby kontynuować, podaj hasło, które jest w pliku informacyjnym dołączonym do systemu: <br />");
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="password" name="beginpass" /><br />
<input type="hidden" name="go" value="1" />
<input type="submit" value="Kontynuuj" />
</form>
<?php
}
} else {
echo("Ze względów bezpieczeństwa wymagane jest podanie prefiksu dla tej instalacji SMPBNS. NIGDY nie instaluj dwóch systemów z tym samym prefiksem! Jeżeli jest to twoja pierwsza i jedyna instalacja SMPBNS, zaleca się pozostawienie domyślnego prefiksu. Prefiks zostanie zapisany nawet, jeżeli instalacja nie zostanie ukończona.<br />");
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="text" name="prefix" value="smpbns_" /><br />
<input type="hidden" name="setprefix" value="1" />
<input type="submit" value="Ustaw prefiks i kontynuuj" />
</form>
<?php
}
?>
</body>
</html>
