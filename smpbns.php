<html>
<head>
<title>Phitherek_' s SMPBNS - Main system file - this title can be changed later</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- CSS style here (optionally) -->
</head>
<body>
<?php
if(file_exists("smpbns_settings.php")) {
	include("smpbns_settings.php");
	$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Can' t connect with MySQL server! Is installation completed?");
	mysql_select_db($dbname);
	$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
	$rows=mysql_num_rows($dball);
	if($rows != NULL) {
		for($id = 1; $id <= $rows; $id++) {
			$query=mysql_query("SELECT title FROM ".$dbprefix."news_main WHERE id=".$id);
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
		$query=mysql_query("SELECT content FROM ".$dbprefix."news_main WHERE id=".$id);
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
		$query=mysql_query("SELECT added FROM ".$dbprefix."news_main WHERE id=".$id);
		$added=mysql_fetch_array($query);
		?>
		<p class="smpbns_date">Last update of this message: <?php echo $added['added']; ?></p><br /><br />
		<?php
		}
	} else {
	?>
<p class="smpbns_info">No records in database</p>
<?php
	}
mysql_close($baza);
} else {
?>
<p class="smpbns_error">Settings file doesn' t exist! Are you sure, that you launched install.php?</p>
<?php
}
?>
<a class="smpbns_admin" href="smpbns_mod.php" title="Moderation">Moderation</a><br />
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_</p>
</body>
</html>
