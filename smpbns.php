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
<title>Phitherek_' s SMPBNS - MOD: ExtensionEngine - Główny plik systemu - ten tytuł można później zmienić</title>
<META http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- Tutaj ewentualnie dołączyć plik stylu CSS -->
</head>
<body>
<?php
if(file_exists("ee_mode.php")) {
		include("ee_mode.php");
	} else {
		$eemode = "locandrem";	
	}
	if($eemode == "local") {
		if(file_exists("ee_list")) {
	$local_eelist = file_get_contents("ee_list");
	} else {
		echo('<p class="ee_error">(ExtensionEngine)(Błąd) Nie znaleziono lokalnego pliku z listą, a tryb ustawiono na &quot;local&quot;! Skontaktuj się z administratorem!</p><br />');	
		}
	} else if($eemode == "locandrem") {
		$official_eelist = extensionengine_get_remote_list("http://www.smpbns.phitherek.cba.pl/download/ee/ee_list");
		if($official_eelist == 2) {
		echo('<p class="ee_error">(ExtensionEngine)(Błąd) Twój serwer nie obsługuje pobierania plików przez wbudowane funkcje PHP ani przez CURL! ExtensionEngine nie potrafi pobrać listy z serwera!</p><br />');	
		}
		if(file_exists("ee_custom")) {
		$eecustom = file_get_contents("ee_custom");
		$feecustom = fopen('data:text/plain,'.$eecustom, 'rb');
		$custom_eelists = array();
		while(($line = fgets($feecustom)) != false) {
		$custom_eelists[] = extensionengine_get_remote_list(trim($line));	
		}
		}
		if(file_exists("ee_list")) {
			$local_eelist = file_get_contents("ee_list");
		}
		} else if($eemode == "locorrem") {
		if(!file_exists("ee_list")) {
		$official_eelist = extensionengine_get_remote_list("http://www.smpbns.phitherek.cba.pl/download/ee/ee_list");	
		if($official_eelist == 2) {
		echo('<p class="ee_error">(ExtensionEngine)(Błąd) Twój serwer nie obsługuje pobierania plików przez wbudowane funkcje PHP ani przez CURL! ExtensionEngine nie potrafi pobrać listy z serwera!</p><br />');	
		}
		if(file_exists("ee_custom")) {
		$eecustom = file_get_contents("ee_custom");
		$feecustom = fopen('data:text/plain,'.$eecustom, 'rb');
		$custom_eelists = array();
		while(($line = fgets($feecustom)) != false) {
		$custom_eelists[] = extensionengine_get_remote_list(trim($line));	
		}	
		}
		} else {
			$local_eelist = file_get_contents("ee_list");
		}
	} else if($eemode == "remote") {
		$official_eelist = extensionengine_get_remote_list("http://www.smpbns.phitherek.cba.pl/download/ee/ee_list");
		if($official_eelist == 2) {
		echo('<p class="ee_error">(ExtensionEngine)(Błąd) Twój serwer nie obsługuje pobierania plików przez wbudowane funkcje PHP ani przez CURL! ExtensionEngine nie potrafi pobrać listy z serwera!</p><br />');	
		} else if($official_eelist == 1) {
		echo('<p class="ee_error">(ExtensionEngine)(Błąd) ExtensionEngine nie może pobrać listy, a tryb ustawiono na &quot;remote&quot;! Skontaktuj się z administratorem!</p><br />');	
		}
		if(file_exists("ee_custom")) {
		$eecustom = file_get_contents("ee_custom");
		$feecustom = fopen('data:text/plain,'.$eecustom, 'rb');
		$custom_eelists = array();
		while(($line = fgets($feecustom)) != false) {
		$custom_eelists[] = extensionengine_get_remote_list(trim($line));	
		}
		}
	}
function extensionengine_parse_menulinks($eelist) {
$feelist = fopen('data:text/plain,'.$eelist, 'rb');
	$action = "detect";
	$name = "";
	while(($line = fgets($feelist)) != false) {
		if($line[0] == '[' and $action == "detect") {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$filename .= $line[$i];
			}
			if(file_exists($filename)) {
			$action = "parse";
			$filename = "";
			continue;
			} else {
			$action = "skip";
			$filename = "";
			continue;
			}
		} else if($action == "parse") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			if($parsed == "name") {
			$action = "name";	
			} else if($parsed == "menulink") {
			$action = "menulink";	
			} else if($parsed == "end") {
			$action = "detect";	
			}
			$parsed = "";
			}
		} else if($action == "skip") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			}
			if($parsed == "end") {
			$action = "detect";
			$parsed = "";
			}
			} else if($action == "name") {
				$name = $line;
				$action = "parse";
			} else if($action == "menulink") {
			echo('<a class="ee_menulink" href="'.trim($line).'">');
			if($name == "") {
				echo("(ExtensionEngine) Nienazwane rozszerzenie</a><br />");
			} else {
				echo("(ExtensionEngine) ".$name."</a><br />");	
			}
			$name = "";
			$action = "parse";
			}
		}
}
function extensionengine_get_remote_list($link) {
if(ini_get('allow_url_fopen') == 1) {
$eelist = file_get_contents($link);
if($eelist != false) {
	return($eelist);
} else {
return(1);	
}
} else {
	if(function_exists('curl_init')) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$eelist = curl_exec($ch);
	curl_close($ch);
	return($eelist);
	} else {
	return(2);	
	}
}
}
function extensionengine_parse_postlinks($eelist, $postid) {
	$feelist = fopen('data:text/plain,'.$eelist, 'rb');
	$action = "detect";
	$name = "";
	while(($line = fgets($feelist)) != false) {
		if($line[0] == '[' and $action == "detect") {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$filename .= $line[$i];
			}
			if(file_exists($filename)) {
			$action = "parse";
			$filename = "";
			continue;
			} else {
			$action = "skip";
			$filename = "";
			continue;
			}
		} else if($action == "parse") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			if($parsed == "name") {
			$action = "name";	
			} else if($parsed == "postlink") {
			$action = "postlink";	
			} else if($parsed == "end") {
			$action = "detect";
			}
			$parsed = "";
			}
		} else if($action == "skip") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			}
			if($parsed == "end") {
			$action = "detect";
			$parsed = "";
			}
			} else if($action == "name") {
				$name = $line;
				$action = "parse";
			} else if($action == "postlink") {
			echo('|<a class="ee_postlink" href="'.trim($line)."postid=".$postid.'">');
			if($name == "") {
				echo("(ExtensionEngine)(Post) Nienazwane rozszerzenie</a>");
			} else {
				echo("(ExtensionEngine)(Post) ".$name."</a>");	
			}
			$name = "";
			$action = "parse";
			}
		}
		echo("<br /><br />");
}
function extensionengine_parse_info($eelist, $type) {
	$feelist = fopen('data:text/plain,'.$eelist, 'rb');
	$action = "detect";
	$name = "";
	$author = "";
	$date = "";
	while(($line = fgets($feelist)) != false) {
		if($line[0] == '[' and $action == "detect") {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$filename .= $line[$i];
			}
			if(file_exists($filename)) {
			$action = "parse";
			$filename = "";
			continue;
			} else {
			$action = "skip";
			$filename = "";
			continue;
			}
			} else if($action == "parse") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			if($parsed == "name") {
			$action = "name";
			} else if($parsed == "author") {
			$action = "author";
			} else if($parsed == "date") {
			$action = "date";	
			} else if($parsed == "end") {
				if($type == 'o') {
				if($name == "") {
					echo("Extension: /unnamed/");
				} else {
					echo("Extension: ".$name);
				}
				} else if($type == 'u') {
					if($name == "") {
						echo("(unofficial) Extension: /unnamed/");
					} else {
						echo("(unofficial) Extension: ".$name);	
					}
				} else if($type == 'l') {
					if($name == "") {
						echo("(local) Extension: /unnamed/");	
					} else {
						echo("(local) Extension: ".$name);	
					}
				}
			if($author != "") {
				if($date == "") {
					echo(" | &copy; by ".$author);	
				} else {
					echo(" | &copy; ".$date." by ".$author);
				}
			}
			echo("<br />");
			$name = "";
			$author = "";
			$date = "";
			$action = "detect";
			}
			}
			$parsed = "";
			} else if($action == "skip") {
			if($line[0] == '[') {
			for($i = 1; $i < strlen($line)-2; $i++) {
				$parsed .= $line[$i];
			}
			}
			if($parsed == "end") {
			$action = "detect";
			$parsed = "";
			}
			} else if($action == "name") {
				$name = $line;
				$action = "parse";
			} else if($action == "date") {
				$date = $line;
				$action = "parse";
			} else if($action == "author") {
			$author = $line;
			$action = "parse";
			}
		}
}

?>
<?php
if(file_exists("smpbns_settings.php")) {
	include("smpbns_settings.php");
	if($eemode == "local") {
		if(isset($local_eelist)) {
		extensionengine_parse_menulinks($local_eelist, $id);
		}
	} else if($eemode == "locandrem") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_menulinks($official_eelist, $id);	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_menulinks($custom_eelist, $id);	
		}
		}
		if(isset($local_eelist)) {
		extensionengine_parse_menulinks($local_eelist, $id);
		}
	} else if($eemode == "locorrem") {
		if(!isset($local_eelist)) {
			if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_menulinks($official_eelist, $id);	
		}
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_menulinks($custom_eelist, $id);	
		}
		}
		} else {
		extensionengine_parse_menulinks($local_eelist, $id);
		}
	} else if($eemode == "remote") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_menulinks($official_eelist, $id);	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_menulinks($custom_eelist, $id);	
		}
		}
	}
	$baza=mysql_connect($serek, $dbuser, $dbpass) or die("Nie można się połączyć z serwerem MySQL! Czy na pewno instalacja dobiegła końca?");
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
		<h3 class="smpbns_title">Brak tytułu</h3><hr />
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
		<p class="smpbns_news">Brak treści</p><hr />
		<?php
		}
		$query=mysql_query("SELECT added FROM ".$dbprefix."news_main WHERE id=".$id);
		$added=mysql_fetch_array($query);
		?>
		<p class="smpbns_date">Ostatnia aktualizacja wiadomości: <?php echo $added['added']; ?></p><br />
		<?php
		if($eemode == "local") {
		if(isset($local_eelist)) {
		extensionengine_parse_postlinks($local_eelist, $id);
		}
	} else if($eemode == "locandrem") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_postlinks($official_eelist, $id);	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_postlinks($custom_eelist, $id);	
		}
		}
		if(isset($local_eelist)) {
		extensionengine_parse_postlinks($local_eelist, $id);
		}
	} else if($eemode == "locorrem") {
		if(!isset($local_eelist)) {
			if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_postlinks($official_eelist, $id);	
		}
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_postlinks($custom_eelist, $id);	
		}
		}
		} else {
		extensionengine_parse_postlinks($local_eelist, $id);
		}
	} else if($eemode == "remote") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_postlinks($official_eelist, $id);	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_postlinks($custom_eelist, $id);	
		}
		}
	}
		}
	} else {
	?>
<p class="smpbns_info">Brak rekordów w bazie danych</p>
<?php
	}
mysql_close($baza);
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
<?php
}
?>
<a class="smpbns_admin" href="smpbns_mod.php" title="Moderacja">Moderacja</a><br />
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_<br />MOD: ExtensionEngine | &copy; 2011 by Phitherek_<br />
<?php
	if($eemode == "local") {
		if(isset($local_eelist)) {
		extensionengine_parse_info($local_eelist, 'l');
		}
	} else if($eemode == "locandrem") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_info($official_eelist, 'o');	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_info($custom_eelist, 'u');	
		}
		}
		if(isset($local_eelist)) {
		extensionengine_parse_info($local_eelist, 'l');
		}
	} else if($eemode == "locorrem") {
		if(!isset($local_eelist)) {
			if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_info($official_eelist, 'o');	
		}
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_info($custom_eelist, 'u');	
		}
		}
		} else {
		extensionengine_parse_info($local_eelist, 'l');
		}
	} else if($eemode == "remote") {
		if(isset($official_eelist)) {
		if($official_eelist != 1 and $official_eelist != 2) {
		extensionengine_parse_info($official_eelist, 'o');	
		}	
		}
		if(isset($custom_eelists)) {
		foreach($custom_eelists as $custom_eelist) {
		extensionengine_parse_info($custom_eelist, 'u');	
		}
		}
	}
	?>
	</p>
</body>
</html>
