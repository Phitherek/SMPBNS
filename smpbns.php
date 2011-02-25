<html>
<head>
<title>Phitherek_' s SMPBNS - MOD: Locked - Główny plik systemu - ten tytuł można później zmienić</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
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
	if($_GET['action'] == "lock") {
		$_SESSION[$prefix.'smpbns_access'] = 0;
		echo("<p class=".'"smpbns_info"'.">Dostęp został ponownie zablokowany!</p><br />");
		session_regenerate_id();
	}
		if($_POST['unlock'] == 1) {
			if($_POST['accpass'] == $accpass) {
			$_SESSION[$prefix.'smpbns_access'] = 1;
			session_regenerate_id();
			}
		} else {
			?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
			Podaj hasło dostępu: <br />
			<input type="password" name="accpass" />
			<input type="hidden" name="unlock" value=1 />
			<input type="submit" value="Odblokuj" />
			</form>
			<?php
		}
	if($_SESSION[$prefix.'smpbns_access'] == 1) {
		?>
		<a class="smpbns_locklink" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=lock">Wyloguj</a><br /><br />
		<?php
	$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
	mysql_select_db($dbname);
	$dball=mysql_query("SELECT * FROM ".$prefix."news_main");
	$rows=mysql_num_rows($dball);
	if($rows != NULL) {
		for($id = 1; $id <= $rows; $id++) {
			$query=mysql_query("SELECT title FROM ".$prefix."news_main WHERE id=".$id);
			$title=mysql_fetch_array($query);
		if($title != NULL) {
		?>
		<h3 class="smpbns_title"><?php echo $title['title']; ?></h3><hr />
		<?php
		} else {
		?>
		<h3 class="smpbns_title">Brak tytułu</h3><hr />
		<?php
		}
		$query=mysql_query("SELECT content FROM ".$prefix."news_main WHERE id=".$id);
		$content=mysql_fetch_array($query);
		if($content != NULL) {
		?>
		<p class="smpbns_news"><?php echo $content['content']; ?></p><hr />
		<?php
		} else {
		?>
		<p class="smpbns_news">Brak treści</p><hr />
		<?php
		}
		$query=mysql_query("SELECT added FROM ".$prefix."news_main WHERE id=".$id);
		$added=mysql_fetch_array($query);
		?>
		<p class="smpbns_date">Ostatnia aktualizacja wiadomości: <?php echo $added['added']; ?></p><br /><br />
		<?php
		}
	} else {
	?>
<p class="smpbns_info">Brak rekordów w bazie danych</p>
<?php
	}
mysql_close($baza);
	}
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
<?php
}
} else {
echo("Ze względów bezpieczeństwa wymagane jest podanie prefiksu dla tej instalacji SMPBNS. NIGDY nie instaluj dwóch systemów z tym samym prefiksem! Jeżeli jest to twoja pierwsza i jedyna instalacja SMPBNS, zaleca się pozostawienie domyślnego prefiksu.<br />");
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="text" name="prefix" value="smpbns_" /><br />
<input type="hidden" name="setprefix" value="1" />
<input type="submit" value="Ustaw prefiks i kontynuuj" />
</form>
<?php
}
?>
<a class="smpbns_admin" href="smpbns_mod.php" title="Moderacja">Moderacja</a><br />
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_<br />
MOD: Locked | &copy; 2010-2011 by Phitherek_</p>
</body>
</html>
