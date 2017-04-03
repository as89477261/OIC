<?php
//global $config; 
if(!defined('ECM_MAINTENANCE')) {
	require_once('../../application/maintenance.php');
} 
if(!ECM_MAINTENANCE || (array_key_exists('webEnabled',$_SESSION) && $_SESSION['webEnabled']))  {
	header('Location: ../');
	die();
}


?>
<html>
	<head>
		<title>BenefitECM : Maintenance Mode</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php 
		//echo $this->headTitle();
		/*
		$this->headLink ()->appendStylesheet ( '/'.$config['runtimeJS'].'/ext'.$config['libVersion']['ext'] .'/resources/css/ext-all.css' );
		echo $this->headLink ();
		
		$this->headScript ()->appendFile ( '/'.$config['runtimeJS'].'/md5.js' );
		$this->headScript ()->appendFile ( '/'.$config['runtimeJS'].'/ext'.$config['libVersion']['ext'] .'/adapter/ext/ext-base.js' );
		$this->headScript ()->appendFile ( '/'.$config['runtimeJS'].'/ext'.$config['libVersion']['ext'] .'/ext-all.js' );
		
	    echo $this->headScript();
	    
	    echo $this->headLink()->appendStylesheet("/{$config['appName']}/css/logon.css");
	    echo $this->headStyle();
		*/
	    
	    ?>
		<style type="text/css">
	    	body {
				background-image: url(../images/bg/bgg.gif);
			}
			
			#redirectwrap{
				background: #F0F5FA;
				border: 1px solid #C2CFDF;
				margin: 200px auto 0 auto;
				text-align: left;
				width: 520px;
			}
			
			#redirectwrap h4{
				background: #D0DDEA;
				border-bottom: 1px solid #C2CFDF;
				color: #3A4F6C;
				font-size: 14px;
				margin: 0;
				padding: 5px;
			}
			
			#redirectwrap p{
				margin: 0;
				padding: 5px;
			}
			
			#redirectwrap p.redirectfoot{
				background: #E3EBF4;
				border-top: 1px solid #C2CFDF;
				text-align: center;
			}
	    </style>
	</head>
	<body>
		<center>
			<div id="redirectwrap">
				<h4>Maintainance Mode</h4>
				<img src="..//images/splash/splash.png" />
				<p>Please contact Administrator</p>
				<p class="redirectfoot">&nbsp;</p>
			</div>
		</center>
	</body>
</html>



