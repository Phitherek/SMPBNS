<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Phitherek_' s SMPBNS - MOD: SLMmed - Logowanie</title>
</head>
<body>
<?php
include("slm_include/loginform.php");
include("slm_include/footer.php");
if(file_exists("smpbns_settings.php")) {
	include("smpbns_settings.php");
if($slmreglock == 1) {
slm_loginpage_main(1,0,"register.php","smpbns.php");
} else {
slm_loginpage_main(1,1,"register.php","smpbns.php");
}
} else {
?>
<p class="smpbns_error">Plik ustawień nie istnieje! Czy na pewno uruchomiłeś install.php?</p>
<?php
}
slm_footer("smpbns.php", "Indeks systemu SMPBNS");
?>
</body>
</html>
