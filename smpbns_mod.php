<?php
function a($url, $text) {
	//echo("DEBUG: Function a - parameters: url: ".htmlspecialchars($url)." text: ".htmlspecialchars($text)."<br />");
	return "<a href=".$url.">".$text."</a>";	
}
function img($url) {
	//echo("DEBUG: Function img - parameters: url: ".htmlspecialchars($url)."<br />");
	return "<img src=".'"'.$url.'"'." />";
}
function ob($type, $data) {
	//echo("DEBUG: Function object - parameters: type: ".htmlspecialchars($type)." data: ".htmlspecialchars($data)."<br />");
	return "<object type=".$type." data=".'"'.$data.'"'.">SMPBNS Code parsed object</object>";
}
function b($text) {
	//echo("DEBUG: Function b - parameters: text: ".htmlspecialchars($text)."<br />");
	return '<font style = "font-weight: bold">'.$text."</font>";	
}
function i($text) {
	//echo("DEBUG: Function i - parameters: text: ".htmlspecialchars($text)."<br />");
	return '<font style = "font-style: italic">'.$text."</font>";	
}
function u($text) {
	//echo("DEBUG: Function u - parameters: text: ".htmlspecialchars($text)."<br />");
	return '<font style = "text-decoration: underline">'.$text."</font>";	
}
function col($col, $text) {
	//echo("DEBUG: Function color - parameters: col: ".htmlspecialchars($col)." text: ".htmlspecialchars($text)."<br />");
	return '<font style = "color: '.$col.'">'.$text."</font>";
}
function parse($toparse) {
	//echo("DEBUG: Function parse START<br />");
	$action = "parse";
	$output = "";
	$code = "";
	$text = "";
	$url = "";
	$type = "";
	$col = "";
	$subaction = "";
	$subcode = "";
	for($i = 0; $i < strlen($toparse); $i++) {
		if($action == "parse") {
			//echo("DEBUG: Action: parse<br />");
			if($toparse[$i] == "\n") {
			//echo("DEBUG: Parsed endl, inserting br<br />");	
			$output .= "<br />";	
			} else if($toparse[$i] == '[') {
			//echo("DEBUG: Code detected, reading...<br />");
			$code = "";
			$action = "getcode";	
			} else {
			//echo("DEBUG: Adding normal char to output...<br />");
			$output .= $toparse[$i];	
			}
		} else if($action == "getcode") {
			//echo("DEBUG: Action: getcode<br />");
			if($toparse[$i] != "]") {
			//echo("DEBUG: Adding normal char to code...<br />");
			$code .= $toparse[$i];	
			} else {
			//echo("DEBUG: End of code detected, parsing...<br />");
			$action = "codeparse";	
			}
		} else if($action == "codeparse") {
			//echo("DEBUG: Action: codeparse<br />");
			if($code == "b") {
			//echo("DEBUG: b code detected, switching to b...<br />");
			$action = "b";
			$subaction = "text";
			} else if($code == "i") {
			//echo("DEBUG: i code detected, switching to i...<br />");
			$action = "i";
			$subaction = "text";
			} else if($code == "u") {
			//echo("DEBUG: u code detected, switching to u...<br />");	
				$action = "u";
				$subaction = "text";
			} else if($code == "img") {
			//echo("DEBUG: img code detected, switching to img...<br />");	
				$action = "img";
				$subaction = "text";
			} else if($code[0] == 'a') {
				//echo("DEBUG: a code detected...<br />");
				if($code[1] == "=") {
				//echo("DEBUG: Custom a code detected, switching to ac...<br />");	
					$action = "ac";
					$subaction = "url";
				} else {
				//echo("DEBUG: Simple a code detected, switching to a...<br />");	
					$action = "a";
					$subaction = "text";
				}
			} else if($code[0] == 'o' AND $code[1] == 'b' AND $code[2] == 'j' AND $code[3] == 'e' AND $code[4] == 'c' AND $code[5] == 't') {
			//echo("DEBUG: object code detected, switching to object...<br />");	
				$action = "object";
				$subaction = "type";
			} else if($code[0] == 'c' AND $code[1] == 'o' AND $code[2] == 'l' AND $code[3] == 'o' AND $code[4] == 'r') {
			//echo("DEBUG: color code detected, switching to color...<br />");	
				$action = "color";
				$subaction = "col";
			}
		} 
		if($action == "b") {
			//echo("DEBUG: Action: b<br />");	
			if($subaction == "code") {
				//echo("DEBUG: b: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/b") {
						//echo("DEBUG: b: Parsed end of b, executing function...<br />");
						$output .= b(parse($text));
						//echo("DEBUG: b: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$text = "";
						$action = "parse";
					} else {
					//echo("DEBUG: b: This code is not the end of b, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
				//echo("DEBUG: b: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: b: Subaction: text<br />");
				if($toparse[$i] == "[") {
				//echo("DEBUG: b: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: b: Getting char to text...<br />");
			$text .= $toparse[$i];	
			}
		} else if($action == "i") {
			//echo("DEBUG: Action: i<br />");
			if($subaction == "code") {
				//echo("DEBUG: i: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/i") {
						//echo("DEBUG: i: Parsed end of i, executing function...<br />");
						$output .= i(parse($text));
						//echo("DEBUG: i: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$text = "";
						$action = "parse";
					} else {
					//echo("DEBUG: i: This code is not the end of i, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
					//echo("DEBUG: i: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: i: Subaction: text<br />");
				if($toparse[$i] == "[") {
				//echo("DEBUG: i: Code detected, switching mode...<br />");	
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: i: Getting char to text...<br />");
			$text .= $toparse[$i];	
			}
		} else if($action == "u") {
			//echo("DEBUG: Action: u<br />");
			if($subaction == "code") {
				//echo("DEBUG: u: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/u") {
						//echo("DEBUG: u: Parsed end of u, executing function...<br />");
						$output .= u(parse($text));
						//echo("DEBUG: u: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$action = "parse";
					} else {
					//echo("DEBUG: u: This code is not the end of u, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
					//echo("DEBUG: u: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: u: Subaction: text<br />");
				if($toparse[$i] == "[") {
				//echo("DEBUG: u: Code detected, switching mode...<br />");	
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: u: Getting char to text...<br />");
			$text .= $toparse[$i];	
			}
		} else if($action == "img") {
			//echo("DEBUG: Action: img<br />");
			if($subaction == "code") {
				//echo("DEBUG: img: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/img") {
						//echo("DEBUG: img: Parsed end of img, executing function...<br />");
						$output .= img(parse($text));
						//echo("DEBUG: img: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$text = "";
						$action = "parse";
					} else {
						//echo("DEBUG: img: This code is not the end of img, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
				//	echo("DEBUG: img: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: img: Subaction: text<br />");
				if($toparse[$i] == "[") {
				//echo("DEBUG: img: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: img: Getting char to text...<br />");
			$text .= $toparse[$i];	
			}
		} else if($action == "a") {
			//echo("DEBUG: Action: a<br />");
			if($subaction == "code") {
				//echo("DEBUG: a: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/a") {
					//	echo("DEBUG: a: Parsed end of a, executing function...<br />");
						$output .= a($text, parse($text));
						//echo("DEBUG: a: Clearing vars and switching back to parse...<br />");
						$text = "";
						$subcode = "";
						$subaction = "";
						$action = "parse";
					} else {
					//echo("DEBUG: a: This code is not the end of a, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
					//echo("DEBUG: a: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: a: Subaction: text<br />");
				if($toparse[$i] == "[") {
				//echo("DEBUG: a: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: a: Getting char to text...<br />");
			$text .= $toparse[$i];	
			}
		} else if($action == "ac") {
			//echo("DEBUG: Action: ac<br />");
			if($subaction == "code") {
				//echo("DEBUG: ac: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/a") {
						//echo("DEBUG: ac: Parsed end of a, executing function...<br />");
						$output .= a($url, parse($text));
						//echo("DEBUG: ac: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$url = "";
						$text = "";
						$action = "parse";
					} else {
					//echo("DEBUG: ac: This code is not the end of a, adding to text and switching mode...<br />");
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
				//echo("DEBUG: ac: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: ac: Subaction: text<br />");
				if($toparse[$i] == "[") {
					//echo("DEBUG: ac: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: ac: Getting char to text...<br />");
			$text .= $toparse[$i];	
			} else if($subaction = "url") {
				//echo("DEBUG: ac: Subaction: url<br />");
				//echo("CODE: ".$code."<br />");
				for($k = 2; $k < strlen($code); $k++) {
					//echo("DEBUG: ac: Reading url from code... (".$k.")<br />");
					$url .= $code[$k];
				}
				//echo("DEBUG: ac: url reading finished, switching mode...<br />");
				$subaction = "text";
				//echo("DEBUG: ac: Getting char to text...<br />");
				$text .= $toparse[$i];
			}
		} else if($action == "object") {
			//echo("DEBUG: Action: object<br />");
			if($subaction == "code") {
				//echo("DEBUG: object: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/object") {
						//echo("DEBUG: object: Parsed end of object, executing function...<br />");
						$output .= ob($type, parse($text));
					//	echo("DEBUG: object: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$type = "";
						$text = "";
						$action = "parse";
					} else {
					//echo("DEBUG: object: This code is not the end of object, adding to text and switching mode...<br />");	
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
				//echo("DEBUG: object: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: object: Subaction: text<br />");
				if($toparse[$i] == "[") {
						//echo("DEBUG: object: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: object: Getting char to text...<br />");
			$text .= $toparse[$i];	
			} else if($subaction = "type") {
				//echo("DEBUG: object: Subaction: type<br />");
				for($k = 6; $k < strlen($code); $k++) {
					//echo("DEBUG: object: Reading type from code... (".$k.")<br />");
					$type .= $code[$k];
				}
				
				//echo("DEBUG: object: type reading finished, switching mode...<br />");
				$subaction = "text";
				//echo("DEBUG: object: Getting char to text...<br />");
			$text .= $toparse[$i];
			}
		} else if($action == "color") {
			//echo("DEBUG: Action: color<br />");
			if($subaction == "code") {
				//echo("DEBUG: color: Subaction: code<br />");
				if($toparse[$i] == "]") {
					if($subcode == "/color") {
						//echo("DEBUG: color: Parsed end of color, executing function...<br />");
						$output .= col($col, parse($text));
						//echo("DEBUG: color: Clearing vars and switching back to parse...<br />");
						$subcode = "";
						$subaction = "";
						$col = "";
						$text = "";
						$action = "parse";
					} else {
					//echo("DEBUG: color: This code is not the end of color, adding to text and switching mode...<br />");	
					$text .= '[';
					$text .= $subcode;
					$text .= $toparse[$i];
					$subcode = "";
					$subaction = "text";
					}
				} else {
				//echo("DEBUG: color: Getting char to subcode...<br />");
				$subcode .= $toparse[$i];	
				}
			} else if($subaction == "text") {
				//echo("DEBUG: color: Subaction: text<br />");
				if($toparse[$i] == "[") {
						//echo("DEBUG: color: Code detected, switching mode...<br />");
				$subaction = "code";
				continue;
				}
				//echo("DEBUG: color: Getting char to text...<br />");
			$text .= $toparse[$i];	
			} else if($subaction = "col") {
				//echo("DEBUG: color: Subaction: col<br />");
				for($k = 6; $k < strlen($code); $k++) {
					//echo("DEBUG: color: Reading color from code... (".$k.")<br />");
					$col .= $code[$k];
				}
				
				//echo("DEBUG: color: color reading finished, switching mode...<br />");
				$subaction = "text";
				//echo("DEBUG: color: Getting char to text...<br />");
			$text .= $toparse[$i];
			}
		}
	}
	//echo("DEBUG: Function parse END <br /> OUTPUT: ".htmlspecialchars($output)."<br />");
	return $output;
}
?>
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
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		if($rows != NULL) {
			for($id = 1; $id <= $rows; $id++) {
				$query=mysql_query("SELECT parse FROM ".$dbprefix."news_main WHERE id=".$id);
				$parse=mysql_fetch_array($query);
				$query=mysql_query("SELECT title FROM ".$dbprefix."news_main WHERE id=".$id);
				$title=mysql_fetch_array($query);
			if($title != NULL) {
			if($parse['parse'] == false OR $parse == NULL) {
			?>
			<h3 class="smpbns_title"><?php echo $title['title']; ?></h3><hr />
			<?php
			} else {
			?>
			<h3 class="smpbns_title"><?php echo parse($title['title']); ?></h3><hr />
			<?php
			}
			} else {
			?>
			<h3 class="smpbns_title">No title</h3><hr />
			<?php
			}
				$query=mysql_query("SELECT content FROM ".$dbprefix."news_main WHERE id=".$id);
				$content=mysql_fetch_array($query);
				if($content != NULL) {
				if($parse['parse'] == false OR $parse == NULL) {	
				?>
				<p class="smpbns_news"><?php echo $content['content']; ?></p><hr />
				<?php
				} else {
				?>
				<p class="smpbns_news"><?php echo parse($content['content']); ?></p><hr />
				<?php	
				}
				} else {
				?>
				<p class="smpbns_news">No content</p><hr />
				<?php
				}
				$query=mysql_query("SELECT added FROM ".$dbprefix."news_main WHERE id=".$id);
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
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$numrows=mysql_num_rows($dball);
		$ai=$numrows+1;
		$query=mysql_query("ALTER TABLE ".$dbprefix."news_main AUTO_INCREMENT = ".$ai);
		if($query != 1) {
		?>
		<p class=smpbns_error>Couldn' t set correct value of AUTO_INCREMENT!</p>
		<?php
		} else {
		if($_POST['parse'] == 1) {
		$parse = true;	
		} else {
		$parse = 0;	
		}
		echo("DEBUG: Query: INSERT INTO ".$dbprefix."news_main VALUES (NULL,".'"'.$_POST['title'].'"'.",".'"'.$_POST['content'].'"'.",NULL,".$parse.")<br />");
		$query=mysql_query("INSERT INTO ".$dbprefix."news_main VALUES (NULL,".'"'.$_POST['title'].'"'.",".'"'.$_POST['content'].'"'.",NULL,".$parse.")");
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
	<textarea name="content" rows=50 cols=50></textarea><br />
	<input type="checkbox" name="parse" value="1" <?php if($parsedefault == 1) echo "checked"; ?> /> <font class="smpbns_parse_checkbox_text">Enable SMPBNS Code Parser for this entry</font><br />
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
		if($_POST['parse'] == 1) {
		$parse = true;	
		} else {
		$parse = 0;	
		}
		$query=mysql_query("UPDATE ".$dbprefix."news_main SET title=".'"'.$_POST['title'].'"'.",content=".'"'.$_POST['content'].'"'.",parse=".$parse." WHERE id=".$_POST['id']);
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
		$query=mysql_query("SELECT parse FROM ".$dbprefix."news_main WHERE id=".$id);
		$parse=mysql_fetch_array($query);	
		$query=mysql_query("SELECT title FROM ".$dbprefix."news_main WHERE id=".$id);
		$title=mysql_fetch_array($query);
		$query=mysql_query("SELECT content FROM ".$dbprefix."news_main WHERE id=".$id);
		$content=mysql_fetch_array($query);
		?>
		<h3 class=smpbns_title>Modifying the entry:</h3><br />
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=news_edit" method="post">
		<input type="text" name="title" value="<?php echo $title['title']; ?>" /><br />
		<textarea name="content" rows=50 cols=50><?php echo $content['content']; ?></textarea><br />
		<input type="checkbox" name="parse" value="1" <?php if($parse['parse'] == true) echo "checked"; ?> /> <font class="smpbns_parse_checkbox_text">Enable SMPBNS Code Parser for this entry</font><br />
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
		$dball=mysql_query("SELECT * FROM ".$dbprefix."news_main");
		$rows=mysql_num_rows($dball);
		$query=mysql_query("DELETE FROM ".$dbprefix."news_main WHERE id=".$id);
		if($query == 1) {
		?>
		<p class=smpbns_info>Entry deleted successfully!</p><br />
		<?php
			$nid=$id+1;
			if($nid<=$rows) {
			for($i=$nid;$i<=$rows;$i++) {
			$query=mysql_query("SELECT added FROM ".$dbprefix."news_main WHERE id=".$i);
			$added=mysql_fetch_array($query);
			$sid=$i-1;
			$query=mysql_query("UPDATE ".$dbprefix."news_main SET id=".$sid." WHERE id=".$i);
			$query=mysql_query("UPDATE ".$dbprefix."news_main SET added=".$added['added']." WHERE id=".$sid);
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
