<html>
<head>
<title>Phitherek_' s SMPBNS - MOD: Locked - Główny plik systemu - ten tytuł można później zmienić</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['started'])) {
session_regenerate_id();
$_SESSION['started'] = true;
}
if(file_exists("smpbns_settings.php")) {
	include("smpbns_settings.php");
	if($_GET['action'] == "lock") {
		$_SESSION['smpbns_access'] = 0;
		session_regenerate_id();
	} else {
	if($_SESSION['smpbns_access'] == 1) {
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
	} else {
		if($_POST['unlock'] == 1) {
			if($_POST['accpass'] == $accpass) {
			$_SESSION['smpbns_access'] = 1;
			session_regenerate_id();
			}
			}
			?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
			Podaj hasło dostępu: <br />
			<input type="password" name="accpass" />
			<input type="hidden" name="unlock" value=1 />
			<input type="submit" value="Odblokuj" />
			</form>
			<?php
		}
	}
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
<?php
}
?>
<a class="smpbns_admin" href="smpbns_mod.php" title="Moderacja">Moderacja</a><br />
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2010 by Phitherek_<br />
MOD: Locked | &copy; 2010 by Phitherek_</p>
</body>
</html>
