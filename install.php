<html>
<head>
<title>Phitherek_' s SMPBNS - Installation</title>
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
if($_POST['beginpass']=="BtW24oPx") { 
	$_SESSION[$prefix.'login'] = 1;
	session_regenerate_id();
}
if($_SESSION[$prefix.'login'] == 1) {
$step = $_POST['go'];
if($step == 4) {
if($_POST['modpass']!=NULL) {	
if($_POST['modpass'] != $_POST['modcheck']) {
$step = 3;
echo("Moderator password does not match repeated moderator password!");
}
} else {
$step = 3;
echo("You haven' t typed moderator password!");
}
}
if($step == 1) {
?>
<h1>Setting MySQL</h1><br />
Do you want to create new MySQL database?<br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="2" />
<input type="submit" value="Yes" />
</form>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="3" />
<input type="submit" value="No" />
</form>

<?php
} else if($step == 2) {
?>
<h1>Setting MySQL</h1><br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
MySQL server adress: <input type="text" name="serek" value="localhost" /><br />
MySQL username: <input type="text" name="dbuser" value="root" /><br />
MySQL password: <input type="password" name="dbpass" /><br />
Name of new database: <input type="text" name="dbname" value="smpbns" /><br />
<input type="hidden" name="go" value="3" />
<input type="hidden" name="newdb" value="1" />
<input type="submit" name="Do it" />
</form>
<?php
} else if($step == 3) {
if($_POST['newdb'] == 1) {
echo("<h1>Setting MySQL</h1><br />");
$baza=mysql_connect($_POST['serek'],$_POST['dbuser'],$_POST['dbpass']) 
or die("Failed to connect with MySQL server!");
echo("Connected with MySQL server!<br />");
$zapytanie=mysql_query("CREATE DATABASE ".$_POST['dbname']);
if($zapytanie == 1) {
echo("New database created sucessfully!<br />");
} else {
?>
Error during creation of new database!<br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="2" />
<input type="submit" value="Back" />
</form>
<?php
}
echo("Closing connection with MySQL server...<br />");
mysql_close($baza);
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
MySQL server adress: <input type="text" name="serek" value="<?php echo $_POST['serek']; ?>" /><br />
MySQL username: <input type="text" name="dbuser" value="<?php echo $_POST['dbuser']; ?>" /><br />
MySQL password: <input type="password" name="dbpass" /><br />
Name of database: <input type="text" name="dbname" value="<?php echo $_POST['dbname']; ?>" /><br />
Table prefix: <input type="text" name="prefix" value="smpbns_" /><br />
Moderator password: <input type="password" name="modpass" /><br />
Repeat moderator password: <input type="password" name="modcheck" /><br />
<input type="hidden" name="go" value="4" />
<input type="submit" value="Do it and save" />
</form>
<?php
} else {
?>
<h1>Setting MySQL</h1><br /><br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
MySQL server adress: <input type="text" name="serek" value="localhost" /><br />
MySQL username: <input type="text" name="dbuser" value="root" /><br />
MySQL password: <input type="password" name="dbpass" /><br />
Name of database: <input type="text" name="dbname" value="smpbns" /><br />
Table prefix: <input type="text" name="prefix" value="smpbns_" /><br />
Moderator password: <input type="password" name="modpass" /><br />
Repeat moderator password: <input type="password" name="modcheck" /><br />
<input type="hidden" name="go" value="4" />
<input type="submit" value="Do it and save" />
</form>

<?php
}
} else if($step==4) {
echo("<h1>Setting MySQL and saving settings</h1><br /><br />");
$baza=mysql_connect($_POST['serek'],$_POST['dbuser'],$_POST['dbpass'])
or die("Failed to connect with MySQL server!");
echo("Connected with MySQL server!<br />");
mysql_select_db($_POST['dbname']);
$zapytanie=mysql_query("CREATE TABLE ".$_POST['prefix']."news_main (id INT NOT NULL AUTO_INCREMENT, title VARCHAR(100), content TEXT, added TIMESTAMP, PRIMARY KEY(id))");
if($zapytanie == 1) {
echo("Table created successfully!<br />");
} else {
?>
Error! Table was not created! Settings will not be saved!<br />
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="hidden" name="go" value="3" />
<input type="submit" value="Back" />
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
fputs($ustawienia,'$prefix="'.$_POST['prefix'].'"'.";\n");
fputs($ustawienia,'$modpass="'.$_POST['modpass'].'"'.";\n");
fputs($ustawienia,'?>');
flock($ustawienia,LOCK_UN);
fclose($ustawienia);
if(file_exists("smpbns_settings.php")) {
echo("Settings saved!<br />");
} else {
echo("Settings could not be saved! Check, if directory with SMPBNS files has 777 (rwxrwxrwx) privileges. If not, change them, then delete table (prefix)_news_main (and database) from your MySQL server, restart your browser and run this install.php again.<br />");
}
echo("<br /> End of installation! IMPORTANT: Delete this install.php from your server, so that no-one will be able to change your settings!");
}
}
} else {
echo("Enter password given in information file attached to the system to continue: <br />");
?>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<input type="password" name="beginpass" /><br />
<input type="hidden" name="go" value="1" />
<input type="submit" value="Continue" />
</form>
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
</body>
</html>
