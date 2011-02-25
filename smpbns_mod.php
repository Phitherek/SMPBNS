<html>
<head>
<title>Phitherek_' s SMPBNS - MOD: Locked - System moderacji - tytuł może być później zmieniony</title>
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
	if($_POST['modlogin'] == 1) {
	if($_POST['modlogin_pass'] == $modpass) {
	$_SESSION[$prefix.'mod_login'] = 1;
	session_regenerate_id();
	}
	}
	if($_SESSION[$prefix.'mod_login'] == 1) {
	if(file_exists("install.php")) {
	?>
	<p class="smpbns_error">Poważne zagrożenie bezpieczeństwa - nie usunąłeś install.php!</p><br /><br />
	<?php
	}
	?>
	<h2 class="smpbns_modmenu">Menu systemu moderacji:</h2><br /><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_list" title="Wyświetl i moderuj aktualności">Wyświetl i moderuj aktualności</a><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=add_new" title="Dodaj nową wiadomość">Dodaj nową wiadomość</a><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=logout" title="Wyloguj">Wyloguj</a><br />
	<hr />
	<?php
	if($_GET['action'] == "news_list") {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
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
				<p class="smpbns_date">Ostatnia aktualizacja wiadomości: <?php echo $added['added']; ?></p><br />
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_edit" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Edytuj" />
				</form>
				<br />
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_delete" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Usuń" />
				</form>
				<?php
			}
		} else {
		?>
		<p class="smpbns_info">Brak rekordów w bazie danych</p>
		<?php
		}
		mysql_close($baza);
	} else if($_GET['action'] == "add_new") {
	if($_POST['newset'] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$prefix."news_main");
		$numrows=mysql_num_rows($dball);
		$ai=$numrows+1;
		$query=mysql_query("ALTER TABLE ".$prefix."news_main AUTO_INCREMENT = ".$ai);
		if($query != 1) {
		?>
		<p class=smpbns_error>Nie udało się ustawić poprawnej wartości AUTO_INCREMENT!</p>
		<?php
		} else {	
		$query=mysql_query("INSERT INTO ".$prefix."news_main VALUES (NULL,".'"'.$_POST['title'].'"'.",".'"'.$_POST['content'].'"'.",NULL)");
		if($query == 1) {
		?>
		<p class="smpbns_info">Wpis został dodany!</p><br />
		<?php
		} else {
		?>
		<p class="smpbns_error">Nie udało się dodać wpisu!</p><br />
		<?php
		}
		}
		mysql_close($baza);
	} else {
	?>
	<h3 class="smpbns_title">Dodawanie nowego wpisu:</h3><br /><br />
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=add_new" method="post">
	<input type="text" name="title" /><br />
	<textarea name="content" rows=50 cols=50>
	</textarea><br />
	<input type="hidden" name="newset" value="1" />
	<input type="submit" value="Dodaj" />
	</form>
	<br />
	<?php
	}
	} else if($_GET['action'] == "news_edit") {
		if($_POST['edset'] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$query=mysql_query("UPDATE ".$prefix."news_main SET title=".'"'.$_POST['title'].'"'.",content=".'"'.$_POST['content'].'"'." WHERE id=".$_POST['id']);
		if($query == 1) {
		?>
		<p class="smpbns_info">Wpis zaktualizowany pomyślnie!</p><br />
		<?php
		} else {
		?>
		<p class="smpbns_error">Nie udało się zaktualizować wpisu!</p><br />
		<?php
		}
		} else {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$id = $_POST['id'];
		if($id != NULL) {
		$query=mysql_query("SELECT title FROM ".$prefix."news_main WHERE id=".$id);
		$title=mysql_fetch_array($query);
		$query=mysql_query("SELECT content FROM ".$prefix."news_main WHERE id=".$id);
		$content=mysql_fetch_array($query);
		?>
		<h3 class=smpbns_title>Modyfikacja wpisu:</h3><br />
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_edit" method="post">
		<input type="text" name="title" value="<?php echo $title['title']; ?>" /><br />
		<textarea name="content" rows=50 cols=50>
		<?php echo $content['content']; ?>
		</textarea><br />
		<input type="hidden" name="edset" value="1" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="submit" value="Zapisz" />	
		</form>
		<?php
		mysql_close($baza);
		} else {
		?>
		<p class="smpbns_error">Nie udało się wczytać ID wiadomości! Wpis nie może zostać zmodyfikowany!</p><br />
		<?php
		mysql_close($baza);
		}
		}
	} else if($_GET['action'] == "news_delete") {
		?>
		<h3 class="smpbns_title">Usuwanie wpisu</h3><br />
		<?php
		$id=$_POST['id'];
		if($id != NULL) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$prefix."news_main");
		$rows=mysql_num_rows($dball);
		$query=mysql_query("DELETE FROM ".$prefix."news_main WHERE id=".$id);
		if($query == 1) {
		?>
		<p class=smpbns_info>Wpis został pomyślnie usunięty!</p><br />
		<?php
			$nid=$id+1;
			if($nid<=$rows) {
			for($i=$nid;$i<=$rows;$i++) {
			$query=mysql_query("SELECT added FROM ".$prefix."news_main WHERE id=".$i);
			$added=mysql_fetch_array($query);
			$sid=$i-1;
			$query=mysql_query("UPDATE ".$prefix."news_main SET id=".$sid." WHERE id=".$i);
			$query=mysql_query("UPDATE ".$prefix."news_main SET added=".$added['added']." WHERE id=".$sid);
			}
			mysql_close($baza);
			}
		} else {
		?>
		<p class="smpbns_error">Nie udało się wczytać ID wiadomości! Wpis nie mógł zostać usunięty!</p><br />
		<?php
		}
		}
	} else if($_GET['action'] == "logout") {
		$_SESSION[$prefix.'mod_login'] = 0;
		?>
		<p class="smpbns_info">Wylogowano Cię z systemu moderacji SMPBNS! Możesz teraz przejść na stronę główną systemu, lub zalogować się jeszcze raz, ponownie wchodząc na tą stronę.</p>
		<?php
	} else {
	?>
	<p class="smpbns_text">Witaj w systemie moderacji SMPBNS! Wybierz działanie z menu, znajdującego się na górze strony. Kiedy skończysz pracę, wyloguj się.</p>
	<?php
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
<br />
<a class="smpbns_main_link" href="smpbns.php" title="Indeks systemu SMPBNS">Indeks systemu SMPBNS</a><hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_<br />
MOD: Locked | &copy; 2010-2011 by Phitherek_</p>
</body>
</html>
