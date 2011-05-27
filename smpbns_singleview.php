<?php
if(file_exists("smpbns_settings.php")) {
include("smpbns_settings.php");
if($_GET["action"] == "show") {
	if(isset($_GET["postid"])) {
		$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
	mysql_select_db($dbname);
	$query=mysql_query("SELECT * FROM ".$dbprefix."news_main WHERE id=".$_GET["postid"]);
	if(!$query) {
		echo('<p class="smpbns_error">Błąd: Nie udało się wczytać postu! Wyświetlam listę...</p><br />');
		$action = "list";
	} else {
	$post = mysql_fetch_array($query);
	?>
	<html>
	<head>
	<title><?php echo $post["title"]; ?> - powered by SingleView Extension for Phitherek_' s SMPBNS</title>
	<META http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
	</head>
	<body>
	<?php
	if($post['title'] != NULL) {
		?>
		<h3 class="smpbns_title"><?php echo $post['title']; ?></h3><hr />
		<?php
		} else {
		?>
		<h3 class="smpbns_title">Brak tytułu</h3><hr />
		<?php
		}
		if($post['content'] != NULL) {
		?>
		<p class="smpbns_news"><?php echo $post['content']; ?></p><hr />
		<?php
		} else {
		?>
		<p class="smpbns_news">Brak treści</p><hr />
		<?php
		}
		?>
		<p class="smpbns_date">Ostatnia aktualizacja wiadomości: <?php echo $post['added']; ?></p><br />
	<?php
	}
	mysql_close($baza);
	} else {
	echo('<p class="smpbns_error">Nie udało się wczytać ID postu! Wyświetlam listę...</p><br />');
	$action = "list";	
	}
	?>
	<br />
	<a class="smpbns_main_link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=list">Lista aktualności SingleView</a><br />
	<?php
}
if($_GET["action"] == "list" OR $action == "list") {
	?>
	<html>
	<head>
	<title>Lista aktualności - powered by SingleView Extension for Phitherek_' s SMPBNS</title>
	<META http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
	</head>
	<body>
	<h3 class="smpbns_title">Lista aktualności SingleView:</h3><br />
	<?php
	$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
	mysql_select_db($dbname);
	$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
	$rows=mysql_num_rows($dball);
	if($rows != NULL) {
		for($id = 1; $id <= $rows; $id++) {
			$query=mysql_query("SELECT title FROM ".$dbprefix."news_main WHERE id=".$id);
			$title=mysql_fetch_array($query);
		if($title != NULL) {
		?>
		<a class="smpbns_link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=show&postid=<?php echo $id; ?>"><?php echo $title['title']; ?></a><br />
		<?php
		} else {
		?>
		<a class="smpbns_link" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=show&postid=<?php echo $id; ?>">Brak tytułu</a><br />
		<?php
		}
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
?>
<br />
<a class="smpbns_main_link" href="smpbns.php">Indeks systemu SMPBNS</a><br />
<hr />
<p class="smpbns_footer">Powered by SingleView Extension for Phitherek_' s SMPBNS | &copy; 2011 by Phitherek_</p><br />
</body>
</html>
