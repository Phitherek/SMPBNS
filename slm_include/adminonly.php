<?php
function slm_adminonly($link="index.php",$footerlinkpage="index.php", $footerlinktext="Indeks") {
	global $prefixexists;
	global $prefix;
	if(!$prefixexists) {
	include("slm_include/prefixinclude.php");
	prefixinclude("smpbns_prefix.php");
	}
	session_start();
	if (!isset($_SESSION[$prefix.'started'])) {
	session_regenerate_id();
	$_SESSION[$prefix.'started'] = true;
}
	if($_SESSION[$prefix.'slm_type'] != "admin") {
		?>
		<p class="slm_error">Ta strona dostępna jest tylko dla użytkownika SLM ze statusem administratora! Jeżeli powinieneś mieć do niej dostęp, skontaktuj się z administratorem.<br /><br /><a class="slm_link" href="<?php echo $link; ?>" alt="link">Przejdź do strony głównej</a></p><br /><br />
		<?php
		?>
<hr />
<p class="smpbns_footer">Powered by <a class="smpbns_footer" href="http://www.smpbns.phitherek.cba.pl" title="SMPBNS">SMPBNS</a> | &copy; 2009-2011 by Phitherek_<br />
MOD: SLMmed | &copy; 2011 by Phitherek_ | uses SLM | &copy; 2010-2011 by Phitherek_<br />
MOD: Comments | &copy; 2011 by Phitherek_<br /><br />
<a class="slm_link" href="<?php echo $footerlinkpage; ?>" alt="link"><?php echo $footerlinktext; ?></a></p>
<?php
		die();
	}
}
?>
