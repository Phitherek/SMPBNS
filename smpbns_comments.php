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
if(file_exists("smpbns_settings.php")) {
include("smpbns_settings.php");
global $prefix;
global $prefixexists;
include("slm_include/userinfo.php");
if($_GET["action"] == "list") {
	if(isset($_GET["postid"])) {
		$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
	mysql_select_db($dbname);
	$query=mysql_query("SELECT * FROM ".$dbprefix."news_main WHERE id=".$_GET["postid"]);
	if(!$query) {
		echo('<p class="smpbns_error">Błąd: Nie udało się wczytać postu!');
	} else {
	$post = mysql_fetch_array($query);
	?>
	<html>
	<head>
	<title><?php echo $post["title"]; ?> - Komentarze - powered by Phitherek_' s SMPBNS MOD: Comments</title>
	<META http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
	</head>
	<body>
	<?php
	slm_userinfo();
	if($post['title'] != NULL) {
		if($post['parse'] == false OR $parse == NULL) {
			?>
			<h3 class="smpbns_title"><?php echo $post['title']; ?></h3><hr />
			<?php
			} else {
			?>
			<h3 class="smpbns_title"><?php echo parse($post['title']); ?></h3><hr />
			<?php
			}
		} else {
		?>
		<h3 class="smpbns_title">Brak tytułu</h3><hr />
		<?php
		}
		if($post['content'] != NULL) {
		if($post['parse'] == false OR $parse == NULL) {	
				?>
				<p class="smpbns_news"><?php echo $post['content']; ?></p><hr />
				<?php
				} else {
				?>
				<p class="smpbns_news"><?php echo parse($post['content']); ?></p><hr />
				<?php	
				}
		} else {
		?>
		<p class="smpbns_news">Brak treści</p><hr />
		<?php
		}
		?>
		<p class="smpbns_date">Wiadomość dodał(a): <?php echo $post['user']; ?></p><br /><br />
		<p class="smpbns_date">Ostatnia aktualizacja wiadomości: <?php echo $post['added']; ?> przez: <?php echo $post['umod']; ?></p><hr />
		<p class="smpbns_comments_list_title">Komentarze:</p><br /><br /><?	
		$query=mysql_query("SELECT * FROM ".$dbprefix."news_comments WHERE postid=".$_GET['postid']);
		$num = mysql_num_rows($query);
		if($num != NULL) {
		while($row = mysql_fetch_array($query)) {
				if($row['content'] != NULL) {
				?>
				<p class="smpbns_comment_content"><?php echo htmlspecialchars($row['content']); ?></p><hr />
				<?php
				} else {
				?>
				<p class="smpbns_comment_content">Brak treści</p><hr />
				<?php
				}
				?>
				<p class="smpbns_comment_user">Dodał(a): <?php echo $row['user']; ?></p><br />
				<p class="smpbns_comment_date">Ostatnia modyfikacja komentarza: <?php echo $row['added']; ?></p><br />
				<?php
				if($_SESSION[$prefix."slm_username"] == $row['user']) {
				?>
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=comment_edit" method="post">
				<input type="hidden" name="id" value=<?php echo $row['id']; ?> />
				<input type="submit" value="Edytuj" />
				</form>
				<br />
				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=comment_delete" method="post">
				<input type="hidden" name="id" value=<?php echo $row['id']; ?> />
				<input type="submit" value="Usuń" />
				</form>
				<?php
				}
				?>
				<hr />
				<?php
		}
		} else {
		echo('<p class="smpbns_info">Brak komentarzy</p><br /><br />');	
		}
		if($_SESSION[$prefix."slm_loggedin"] == 1) {
			?>
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=comment_add" method="post">
			<textarea cols=25 rows=25 name="content"></textarea><br />
			<input type="hidden" name="postid" value=<?php echo $_GET['postid']; ?> />
			<input type="submit" value="Dodaj komentarz" />
			</form>
			<?php
		}
	}
	mysql_close($baza);
	} else {
	echo('<p class="smpbns_error">Nie udało się wczytać ID postu!</p><br />');	
	}
	?>
	<br />
	<?php
} else if($_GET['action'] == "comment_add") {
?>
<html>
<head>
<title>Dodawanie komentarza - powered by Phitherek_' s SMPBNS MOD: Comments</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
</head>
<body>
<?php
slm_userinfo();
?>
<h3 class="smpbns_title">Dodawanie komentarza</h3><br />
<?php
if(isset($_POST["postid"])) {
	if($_SESSION[$prefix."slm_loggedin"] == 1) {
		$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
	mysql_select_db($dbname);
	$query=mysql_query("INSERT INTO ".$dbprefix."news_comments VALUES(NULL,".'"'.$_POST["content"].'",'."NULL,".'"'.$_SESSION[$prefix."slm_username"].'",'.$_POST["postid"].")");
	if($query) {
	echo('<p class="smpbns_info">Komentarz dodany pomyślnie!</p>');	
	} else {
	echo('<p class="smpbns_error">Nie udało się dodać komentarza!</p><br />');	
	}
	} else {
	echo('<p class="smpbns_error">Nie jesteś zalogowany!</p><br />');	
	}
} else {
	echo('<p class="smpbns_error">Nie udało się wczytać ID postu!</p><br />');
}
} else if($_GET['action'] == "comment_edit") {
?>
<html>
<head>
<title>Modyfikacja komentarza - powered by Phitherek_' s SMPBNS MOD: Comments</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
</head>
<body>
<?php
slm_userinfo();
?>
<h3 class="smpbns_title">Modyfikacja komentarza</h3><br />
<?php
if($_POST['cedset'] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$query=mysql_query("UPDATE ".$dbprefix."news_comments SET content=".'"'.$_POST['content'].'"'." WHERE id=".$_POST['id']);
		if($query == 1) {
		?>
		<p class="smpbns_info">Komentarz zaktualizowany pomyślnie!</p><br />
		<?php
		} else {
		?>
		<p class="smpbns_error">Nie udało się zaktualizować komentarza!</p><br />
		<?php
		}
		} else {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$id = $_POST['id'];
		if($id != NULL) {
			if($_SESSION[$prefix."slm_loggedin"] == 1) {
		$query=mysql_query("SELECT user FROM ".$dbprefix."news_comments WHERE id=".$id);
		$user=mysql_fetch_array($query);
		if($_SESSION[$prefix."slm_username"] == $user["user"]) {
		$query=mysql_query("SELECT content FROM ".$dbprefix."news_comments WHERE id=".$id);
		$content=mysql_fetch_array($query);
		?>
		<form action="<?php echo $_SERVER["PHP_SELF"]; ?>?action=comment_edit" method="post">
		<textarea name="content" rows=25 cols=25><?php echo $content['content']; ?></textarea><br />
		<input type="hidden" name="cedset" value="1" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="submit" value="Zapisz" />	
		</form>
		<?php
		} else {
			?>
			<p class="smpbns_error">Nie jesteś autorem tego komentarza! Komentarz nie może zostać zmodyfikowany!</p><br />
			<?php
		}
		mysql_close($baza);
			} else {
			?>
			<p class="smpbns_error">Nie jesteś zalogowany! Komentarz nie może zostać zmodyfikowany!</p><br />
			<?php
			}
		} else {
		?>
		<p class="smpbns_error">Nie udało się wczytać ID komentarza! Komentarz nie może zostać zmodyfikowany!</p><br />
		<?php
		mysql_close($baza);
		}
		}	
} else if($_GET['action'] == "comment_delete") {
	?>
<html>
<head>
<title>Usuwanie komentarza - powered by Phitherek_' s SMPBNS MOD: Comments</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
</head>
<body>
<?php
slm_userinfo();
?>
<h3 class="smpbns_title">Usuwanie komentarza</h3><br />
<?php
$id=$_POST['id'];
		if($id != NULL) {
			if($_SESSION[$prefix."slm_loggedin"] == 1) {
		$baza=mysql_connect($serek,$dbuser,$dbpass) or die("Nie można połączyć się z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
		mysql_select_db($dbname);
		$query=mysql_query("SELECT user FROM ".$dbprefix."news_comments WHERE id=".$id);
		$user=mysql_fetch_array($query);
		if($_SESSION[$prefix."slm_username"] == $user['user']) {
		$query=mysql_query("DELETE FROM ".$dbprefix."news_comments WHERE id=".$id);
		if($query == 1) {
		?>
		<p class=smpbns_info>Komentarz został pomyślnie usunięty!</p><br />
		<?php
		} else {
		?>
		<p class=smpbns_error>Nie udało się usunąć komentarza!</p><br />
		<?php	
		}
		} else {
			?>
			<p class="smpbns_error">Nie jesteś autorem tego komentarza! Komentarz nie może zostać usunięty!</p><br />
			<?php
		}
			mysql_close($baza);
			} else {
				?>
			<p class="smpbns_error">Nie jesteś zalogowany! Komentarz nie może zostać usunięty!</p><br />
			<?php
			}
		} else {
		?>
		<p class="smpbns_error">Nie udało się wczytać ID komentarza! komentarz nie mógł zostać usunięty!</p><br />
		<?php
		}
} else {
?>
	<html>
	<head>
	<title>Brak akcji! - Komentarze - powered by Phitherek_' s SMPBNS MOD: Comments</title>
	<META http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
	</head>
	<body>
	<p class="smpbns_error">Brak akcji!</p><br />
	<?php	
}
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
<?php
}
?>
<br />
<a class="smpbns_main_link" href="smpbns.php">Indeks systemu SMPBNS</a><br />
<a class="smpbns_admin" href="smpbns_mod.php">Moderacja</a><br />
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_<br />
MOD: SLMmed | &copy; 2011 by Phitherek_ | uses SLM | &copy; 2010-2011 by Phitherek_<br />
MOD: Comments | &copyl 2011 by Phitherek_</p>
</body>
</html>
