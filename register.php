<html>
<head>
<title>Phitherek_' s SMPBNS - MOD: SLMmed - Rejestracja użytkownika SLM</title>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
if($slmreglock == 1) {
include("slm_include/adminonly.php");
slm_adminonly("smpbns.php","smpbns.php","Indeks systemu SMPBNS");
}
include("slm_include/register.php");
include("slm_include/footer.php");
slm_register(1,"smpbns_mod.php");
slm_footer("smpbns_mod.php","Moderacja SMPBNS");
?>
</body>
</html>
