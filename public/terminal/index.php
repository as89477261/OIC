<?php
global $config;

if($_SESSION ['term'] ['loggedIn']) {
	$prompt = "<font color=\"blue\">[ECM]</font>&gt; ";
} else {
	$prompt = "<font color=\"red\">[$]</font>&gt; ";
}
//$prompt = htmlspecialchars($prompt);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">


<title>Benefit&copy;ECM Web-based terminal</title>
<style type="text/css">
body {
	margin: 0;
	font-size: 12px;
}
</style>
</head>
<body>

<div id="terminal_container"></div>

<script type="text/javascript"
	src="../js/jquery-1.3.2.js"></script>
<script type="text/javascript"
	src="../js/jquery.terminal.js"></script>
<script type="text/javascript">
jQuery.noConflict();
var $j = jQuery;
$j('#terminal_container').height($j(document).height());
$j('#terminal_container').terminal('server.php', {custom_prompt : '<?php echo $prompt; ?>', hello_message : 'Welcome to ECM administrator console.<br/>Please Login to gain access.<br/>Session ID : [<?php echo session_id();?>]'});
</script>
</body>
</html>