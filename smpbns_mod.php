<html>
<head>
<title>Phitherek_' s SMPBNS - Moderation system - this title can be changed later</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- CSS style here (optionally) -->
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
echo("A prefix was saved successfully!<br />");	
} else {
echo("Could not save the file with prefix! Check directory privileges and try again!<br />");	
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
	<p class="smpbns_error">Serious security risk - you haven' t deleted install.php!</p><br /><br />
	<?php
	}
	?>
	<h2 class="smpbns_modmenu">Moderation system menu:</h2><br /><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_list" title="Show and moderate news">Show and moderate news</a><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=add_new" title="Add a new message">Add a new message</a><br />
	<a class="smpbns_modmenu" href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=logout" title="Logout">Logout</a><br />
	<hr />
	<?php
	if($_GET['action'] == "news_list") {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
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
			<h3 class="smpbns_title">No title</h3><hr />
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
				<p class="smpbns_news">No content</p><hr />
				<?php
				}
				$query=mysql_query("SELECT added FROM ".$prefix."news_main WHERE id=".$id);
				$added=mysql_fetch_array($query);
				?>
				<p class="smpbns_date">Last update of this message: <?php echo $added['added']; ?></p><br />
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_edit" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Edit" />
				</form>
				<br />
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_delete" method="post">
				<input type="hidden" name="id" value=<?php echo $id; ?> />
				<input type="submit" value="Delete" />
				</form>
				<?php
			}
		} else {
		?>
		<p class="smpbns_info">No records in database</p>
		<?php
		}
		mysql_close($baza);
	} else if($_GET['action'] == "add_new") {
	if($_POST['newset'] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$prefix."news_main");
		$numrows=mysql_num_rows($dball);
		$ai=$numrows+1;
		$query=mysql_query("ALTER TABLE ".$prefix."news_main AUTO_INCREMENT = ".$ai);
		if($query != 1) {
		?>
		<p class=smpbns_error>Couldn' t set correct value of AUTO_INCREMENT!</p>
		<?php
		} else {	
		$query=mysql_query("INSERT INTO ".$prefix."news_main VALUES (NULL,".'"'.$_POST['title'].'"'.",".'"'.$_POST['content'].'"'.",NULL)");
		if($query == 1) {
		?>
		<p class="smpbns_info">Entry added successfully!</p><br />
		<?php
		} else {
		?>
		<p class="smpbns_error">Entry couldn' t be added!</p><br />
		<?php
		}
		}
		mysql_close($baza);
	} else {
	?>
	<h3 class="smpbns_title">Adding a new entry:</h3><br /><br />
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=add_new" method="post">
	<input type="text" name="title" /><br />
	<textarea name="content" rows=50 cols=50>
	</textarea><br />
	<input type="hidden" name="newset" value="1" />
	<input type="submit" value="Add" />
	</form>
	<br />
	<?php
	}
	} else if($_GET['action'] == "news_edit") {
		if($_POST['edset'] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
		mysql_select_db($dbname);
		$query=mysql_query("UPDATE ".$prefix."news_main SET title=".'"'.$_POST['title'].'"'.",content=".'"'.$_POST['content'].'"'." WHERE id=".$_POST['id']);
		if($query == 1) {
		?>
		<p class="smpbns_info">Entry updated successfully!</p><br />
		<?php
		} else {
		?>
		<p class="smpbns_error">Entry couldn' t be updated!</p><br />
		<?php
		}
		} else {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
		mysql_select_db($dbname);
		$id = $_POST['id'];
		if($id != NULL) {
		$query=mysql_query("SELECT title FROM ".$prefix."news_main WHERE id=".$id);
		$title=mysql_fetch_array($query);
		$query=mysql_query("SELECT content FROM ".$prefix."news_main WHERE id=".$id);
		$content=mysql_fetch_array($query);
		?>
		<h3 class=smpbns_title>Modifying the entry:</h3><br />
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_edit" method="post">
		<input type="text" name="title" value="<?php echo $title['title']; ?>" /><br />
		<textarea name="content" rows=50 cols=50>
		<?php echo $content['content']; ?>
		</textarea><br />
		<input type="hidden" name="edset" value="1" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="submit" value="Save" />	
		</form>
		<?php
		mysql_close($baza);
		} else {
		?>
		<p class="smpbns_error">Couldn' t read the ID of the message! Entry can' t be modified!</p><br />
		<?php
		mysql_close($baza);
		}
		}
	} else if($_GET['action'] == "news_delete") {
		?>
		<h3 class="smpbns_title">Deleting the entry:</h3><br />
		<?php
		$id=$_POST['id'];
		if($id != NULL) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
		mysql_select_db($dbname);
		$dball=mysql_query("SELECT * FROM ".$prefix."news_main");
		$rows=mysql_num_rows($dball);
		$query=mysql_query("DELETE FROM ".$prefix."news_main WHERE id=".$id);
		if($query == 1) {
		?>
		<p class=smpbns_info>Entry deleted successfully!</p><br />
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
		<p class="smpbns_error">Couldn' t read the ID of the message! Entry can' t be deleted!</p><br />
		<?php
		}
		}
	} else if($_GET['action'] == "logout") {
		$_SESSION[$prefix.'mod_login'] = 0;
		?>
		<p class="smpbns_info">You' re now logged out from the moderation system. You can now go to main page of SMPBNS or login again by refreshing this page.</p>
		<?php
	} else {
	?>
	<p class="smpbns_text">Welcome to the SMPBNS moderation system! Choose an action from the menu, which is on the top of this page. When you are finished, log out.</p>
	<?php
	}
	} else {
	?>
	<p class="smpbns_modlogin_text">Type moderator password:</p><br />
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input type="password" name="modlogin_pass" /><br />
		<input type="hidden" name="modlogin" value="1" />
		<input type="submit" value="Login" />
	</form>
<?php
	}
} else {
?>
<p class="smpbns_error">Settings file doesn' t exist! Are you sure, that you launched install.php?</p>
<?php
}
} else {
echo("For security reasons you must set a prefix for this installation of SMPBNS. NEVER install two systems with the same prefix! If it is your first and only installation of SMPBNS, it is recommended to leave the default prefix. The prefix will be saved even if the installation will be uncomplete.<br />");
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="text" name="prefix" value="smpbns_" /><br />
<input type="hidden" name="setprefix" value="1" />
<input type="submit" value="Set prefix and continue" />
</form>
<?php
}
?>
<br />
<a class="smpbns_main_link" href="smpbns.php" title="SMPBNS Index">SMPBNS Index</a><hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_</p>
</body>
</html>
