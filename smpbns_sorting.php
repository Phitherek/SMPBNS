<html>
<head>
<title>Sorting extension for Phitherek_' s SMPBNS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
		if($_GET['action'] == "list") {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		if($rows != NULL) {
			for($id = 1; $id <= $rows; $id++) {
				$query=mysql_query("SELECT title FROM ".$dbprefix."news_main WHERE id=".$id);
				$title=mysql_fetch_array($query);
			if($title != NULL) {
			?>
			<p class="smpbns_news"><?php echo $title['title']; ?></p><hr />
			<?php
			} else {
			?>
			<p class="smpbns_news">Brak tytułu</p><hr />
			<?php
			}	
			?>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=top" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Przesuń na górę" />
				</form>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=bottom" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Przesuń na dół" />
				</form>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=up" method="post">
				Przesuń o: <input type="text" name="number" value=1 />
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="w górę" />
				</form>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=down" method="post">
				Przesuń o: <input type="text" name="number" value=1 />
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="w dół" />
				</form>
				<br />
				<hr />
				<?php
			}
		} else {
		?>
		<p class="smpbns_info">Brak rekordów w bazie danych</p>
		<?php
		}
		mysql_close($baza);
		} else if($_GET['action'] == "top") {
			if($_POST['id'] != NULL) {
			$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		$dest=$rows+1;
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$dest." WHERE id=".$_POST['id']);
		$pid = $_POST['id']-1;
		for($aid = $pid; $aid >= 1; $aid--) {
			$naid = $aid+1;
			$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$naid." WHERE id=".$aid);
		}
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=1 WHERE id=".$dest);
		echo('<p class="smpbns_info">Wpis został zmodyfikowany pomyślnie!</p>');
		mysql_close($baza);
			} else {
				echo('<p class="smpbns_error">Nie udało się wczytać ID postu! Wpis nie zostanie zmodyfikowany!</p><br />');
			}
		} else if($_GET['action'] == "bottom") {
			if($_POST['id'] != NULL) {
			$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		$dest=$rows+1;
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$dest." WHERE id=".$_POST['id']);
		$nid = $_POST['id']+1;
		for($aid = $nid; $aid <= $rows; $aid++) {
			$paid = $aid-1;
			$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$paid." WHERE id=".$aid);
		}
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$rows." WHERE id=".$dest);
		echo('<p class="smpbns_info">Wpis został zmodyfikowany pomyślnie!</p>');
		mysql_close($baza);
			} else {
				echo('<p class="smpbns_error">Nie udało się wczytać ID postu! Wpis nie zostanie zmodyfikowany!</p><br />');
			}
		} else if($_GET['action'] == "up") {
			if($_POST['id'] != NULL) {
				if($_POST['number'] != NULL) {
			$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		if($_POST['id']-$_POST['number'] >= 1) {
		$dest=$rows+1;
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$dest." WHERE id=".$_POST['id']);
		$pid = $_POST['id']-1;
		for($aid = $pid; $aid >= $_POST['id']-$_POST['number']; $aid--) {
			$naid = $aid+1;
			$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$naid." WHERE id=".$aid);
		}
		$end = $_POST['id']-$_POST['number'];
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$end." WHERE id=".$dest);
		echo('<p class="smpbns_info">Wpis został zmodyfikowany pomyślnie!</p>');
		} else {
			echo('<p class="smpbns_error">Wielkość przesunięcia jest za duża! Wpis nie zostanie zmodyfikowany!</p><br />');
			?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=top" method="post">
				<input type="hidden" name="id" value=<?php echo $_POST['id']; ?> />
				<input type="submit" value="Przesuń na górę" />
				</form>
			<?php
		}
		mysql_close($baza);
				} else {
					echo('<p class="smpbns_error">Nie udało się wczytać wielkości przesunięcia! Wpis nie zostanie zmodyfikowany!</p><br />');
				}
				} else {
				echo('<p class="smpbns_error">Nie udało się wczytać ID postu! Wpis nie zostanie zmodyfikowany!</p><br />');
			}
		} else if($_GET['action'] == "down") {
		if($_POST['id'] != NULL) {
				if($_POST['number'] != NULL) {
			$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		if($_POST['id']+$_POST['number'] <= $rows) {
		$dest=$rows+1;
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$dest." WHERE id=".$_POST['id']);
		$nid = $_POST['id']+1;
		for($aid = $nid; $aid <= $_POST['id']+$_POST['number']; $aid++) {
			$paid = $aid-1;
			$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$paid." WHERE id=".$aid);
		}
		$end=$_POST['id']+$_POST['number'];
		$q=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$end." WHERE id=".$dest);
		echo('<p class="smpbns_info">Wpis został zmodyfikowany pomyślnie!</p>');
		} else {
			echo('<p class="smpbns_error">Wielkość przesunięcia jest za duża! Wpis nie zostanie zmodyfikowany!</p><br />');
			?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=bottom" method="post">
				<input type="hidden" name="id" value=<?php echo $_POST['id']; ?> />
				<input type="submit" value="Przesuń na dół" />
				</form>
			<?php
		}
		mysql_close($baza);
				} else {
					echo('<p class="smpbns_error">Nie udało się wczytać wielkości przesunięcia! Wpis nie zostanie zmodyfikowany!</p><br />');
				}
				} else {
				echo('<p class="smpbns_error">Nie udało się wczytać ID postu! Wpis nie zostanie zmodyfikowany!</p><br />');
			}	
		} else {
		echo('<p class="smpbns_error">Nie podano akcji! Spróbuj dopisać ?action=list.</p><br />');	
		}
	} else {
	?>
	<p class="smpbns_modlogin_text">Podaj hasło moderatora:</p><br />
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=list" method="post">
		<input type="password" name="modlogin_pass" /><br />
		<input type="hidden" name="modlogin" value="1" />
		<input type="submit" value="Zaloguj" />
	</form>
	<hr />
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
<a class="smpbns_admin" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=list" title="Lista rozszerzenia Sorting">Lista rozszerzenia Sorting</a><br />
<a class="smpbns_admin" href="smpbns_mod.php" title="Moderacja">Moderacja SMPBNS</a><br />
<p class="smpbns_footer">Powered by Sorting extension for Phitherek_' s SMPBNS | &copy; 2011 by Phitherek_</p>
</body>
</html>
