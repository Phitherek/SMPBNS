<html>
<head>
<title>Phitherek_' s SMPBNS - Aktualizacja do wersji z SMPBNS Code Parser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
if(file_exists("smpbns_settings.php")) {
	include("smpbns_settings.php");
	if($_POST['modlogin'] == 1) {
	if($_POST['modlogin_pass'] == $modpass) {
	$_SESSION[$prefix.'mod_login'] = 1;
	$step = 1;
	session_regenerate_id();
	}
	}
	$step = $_POST['go'];
	if($_SESSION[$prefix.'mod_login'] == 1) {
		if($step == 1) {
		?>
		<p>Ten skrypt wykona odpowiednie zmiany w bazie danych oraz w pliku ustawień na potrzeby nowej wersji SMPBNS core z SMPBNS Code Parser.</p><br /><br />
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="checkbox" name="parsedefault" value="1" checked /> Domyślnie włącz SMPBNS Code Parser<br />
		<input type="hidden" name="go" value="2" />
		<input type="submit" value="Aktualizacja" />
		</form>
		<?php
		} else if($step == 2) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		echo("Połączono z serwerem MySQL!<br />");
		$query = mysql_query("ALTER TABLE ".$dbprefix."_news_main ADD COLUMN parse BOOLEAN NOT NULL");
		if($query == TRUE) {
		echo("Tabela zmodyfikowana pomyślnie!<br />");	
		} else {
		die("Nie udało się zmodyfikować tabeli!");	
		}
		mysql_close($baza);
		$backup = rename("smpbns_settings.php", "smpbns_settings_backup.php");
		if($backup == TRUE) {
		$ustawienia=fopen("smpbns_settings.php","w");
flock($ustawienia,LOCK_EX);
fputs($ustawienia,'<?php '."\n");
fputs($ustawienia,'$serek="'.$serek.'"'.";\n");
fputs($ustawienia,'$dbuser="'.$dbuser.'"'.";\n");
fputs($ustawienia,'$dbpass="'.$dbpass.'"'.";\n");
fputs($ustawienia,'$dbname="'.$dbname.'"'.";\n");
fputs($ustawienia,'$dbprefix="'.$dbprefix.'"'.";\n");
fputs($ustawienia,'$modpass="'.$modpass.'"'.";\n");
if($_POST['parsedefault'] == 1) {
fputs($ustawienia,'$parsedefault='.$_POST['parsedefault'].";\n");
} else {
fputs($ustawienia,'$parsedefault=0'.";\n");	
}
fputs($ustawienia,'?>');
flock($ustawienia,LOCK_UN);
fclose($ustawienia);
if(file_exists("smpbns_settings.php")) {
echo("Ustawienia zostały zapisane!<br />");
unlink("smpbns_settings_backup.php");
echo("Aktualizacja zakończona! WAŻNE: Skasuj ten plik update.php z serwera, aby nikt nie mógł zepsuć Twoich ustawień!");
} else {
echo("Nie można było zapisać ustawień! Sprawdź, czy katalog z plikami systemu SMPBNS ma uprawnienia 777 (lub rwxrwxrwx), jeżeli nie, to zmień je, a następnie uruchom ten plik update.php ponownie!<br />");
rename("smpbns_settings_backup.php", "smpbns_settings.php");
}	
		} else {
		echo("Nie udało się utworzyć kopii zapasowej ustawień!");
		}
		}
	} else {
	?>
	<p class="smpbns_modlogin_text">Podaj hasło moderatora:</p><br />
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="password" name="modlogin_pass" /><br />
		<input type="hidden" name="modlogin" value="1" />
		<input type="submit" value="Zaloguj" />
	</form>
<?php
	}
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
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
