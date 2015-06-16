<?
	error_reporting(E_ALL & ~E_NOTICE);
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Log de Erros</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" 		content="text/html; charset=utf8" />
	<meta name="Keywords"					content="palavra,chave" />
	<meta name="Description"				content="website limpo" />
	<meta name="robots" 					content="index, follow" />
	<meta name="robots" 					content="noarchive" />
	<meta http-equiv="Content-Language"		content="pt-br" />
	<meta http-equiv="Cache-Control"		content="no-cache, no-store" />
	<meta http-equiv="Pragma"				content="no-cache, no-store" />
	<meta http-equiv="Expires" 				content="Wed, 4 Apr 1984 00:00:01 GMT" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
	::-webkit-scrollbar              {
	background: transparent; width:18px;
 }
::-webkit-scrollbar-button       {  							}
::-webkit-scrollbar-track        {  background-image: url("../img/bg/white_wall_hash.png"); }
::-webkit-scrollbar-track-piece  {	 background-image: url("../img/bg/white_wall_hash.png");	}
::-webkit-scrollbar-thumb        {   background-color: rgba(100, 100, 100, .75);							}
::-webkit-scrollbar-corner       { 								}
::-webkit-resizer                {  							}
		*{
			padding:0 0 0 0;
			margin:0 0 0 0;
		}
		body{
			background-color: rgba(10, 10, 10, .1);
			color:#000;
			font-family: futura, arial, sans;
			padding: 30px;
			font-size: 11px;
		}
		h1{
			float: left;
			clear: both;
			width: 100%;
			padding: 5px;
			font-size:22px;
			font-weight: normal;
			border-bottom: 1px dashed rgba(10, 10, 10, .6);
		}
		h2{
			float: left;
			clear: both;
			width: 100%;
			padding: 5px;
			padding-bottom: 0px;
			margin-top: 30px;
			font-size:16px;
			font-weight: normal;
			border-bottom: 1px dashed rgba(10, 10, 10, .6);
		}
		.log{
			float: left;
			width: 100%;
			padding: 5px;
			border: 1px dotted rgba(10, 10, 10, .3);
			border-bottom: 0px;
		}
		.log:nth-child(odd){
			background-color: rgba(255, 255, 255, .15);
		}
		.intern{
			display: none;
		}
		.msg{
			float:left;
		}
		.function{
			float:right;
			clear: none;
			width: 140px;
			padding-right: 10px;
		}
		.class{
			float:right;
			clear: none;
			width: 140px;
			padding-right: 10px;
		}
		.inicio{
			font-weight: bold;
			margin-top: 35px;
			color:#662D91;
			background-color: rgba(41, 171, 226, .2) !important;
		}
		.Mysql, .kit, .run, .Warning{
			color:#9E005D;
		}
		.View{
			color:#0071BC;
		}
		.RouterMethods, .Notice{
			color:#00A99D;
		}
		.RouterPage{
			color:#22B573;
		}
		.RouterBase{
			color:#009245;
		}
		.Debug{
			color:#662D91;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.client{
			float:right;
		}
		.left{
			float:left;
			width: auto;
		}
		.right{
			float:right;
			width: 35%;
			width: auto;
		}
		a{
			text-decoration: none;
			color:#C1272D !important;
			margin-top: 10px;
			display: block;
			float: left;
			clear: both;
		}
		a.right{
			clear: none;
		}
		a:hover{
			color:#ED1C24 !important;
		}
		h2 a {
			font-size: 11px;
			display: block;
			float: right;
			padding-top: 5px;
			margin-top: 0px;
		}
		h1 a {
			font-size: 12px;
			display: block;
			float: right;
			padding-top: 11px;
			margin-top: 0px;
		}
		hr{
			float: left;
			clear: both;
			width: 100%;
			border:1px dotted rgba(10,10,10, .2);
		}
		.dados_servidor div.left{
			margin-right: 20px;
		}
	</style>

</head>
<body>
	<h1>Session</h1>

---------------------<br>
<pre>
<?
    print_r($_SESSION);
?>
</pre>
<br>---------------------
</body>
</html>