<?php

// Pokreni session spremanje kako bi mogli privremeno spremiti uspješnu prijavu korisnika
session_start();

?>
<!DOCTYPE html>
<html>
	<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['prijava'])) {
		
		if (isset($_SESSION['$imePrijavljenogKorisnika'])) {
			unset($_SESSION['$imePrijavljenogKorisnika']);
			unset($_SESSION['$levelPrijavljenogKorisnika']);
		}
		
		$prijavaImeKorisnika = $_POST['imeKorisnika'];
		$prijavaLozinkaKorisnika = md5(htmlspecialchars($_POST['lozinkaKorisnika']));
		
		$dbc = mysqli_connect('localhost', 'root', '', 'projektno') or die('Neuspješno spajanje na bazu.');
		$sql = "SELECT username, password, level FROM users
				WHERE username = ? AND password = ?";
		$stmt = mysqli_stmt_init($dbc);
		if (mysqli_stmt_prepare($stmt, $sql)) {
			mysqli_stmt_bind_param($stmt, 'ss', $prijavaImeKorisnika, $prijavaLozinkaKorisnika);
        	mysqli_stmt_execute($stmt);
        	mysqli_stmt_store_result($stmt);
     	}
		mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika);
		mysqli_stmt_fetch($stmt);

		if (mysqli_stmt_num_rows($stmt) > 0) {
			$uspjesnaPrijava = true;

			if($levelKorisnika == 2) {
				$korisnikJeAdministrator = true;
			}
			else {
				$korisnikJeAdministrator = false;
			}

			$_SESSION['$imePrijavljenogKorisnika'] = $imeKorisnika;
			$_SESSION['$levelPrijavljenogKorisnika'] = $levelKorisnika;
		} else {
			$uspjesnaPrijava = false;
		}
		mysqli_close($dbc);
	}
}
	
	

			?>
<head>
	<meta charset="UTF-8"/>
	<title>Pick-a-Book</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" type="text/css" href="unos.css">
</head>
<style>
	body{
	 background-image: url("poz2.jpg");
	 background-size: 1800px 1000px;
	}
	
	.wrapper{
		max-width:900px;
		margin:0 auto;
	}
	.logo{
		width:100%;
		float:left;
		background:white;

	}
	.slika1{
		width:30%;
		float:left;
		background:white;
		
	}
	.slika1 img{
		display: block;
		margin-left: auto;
		margin-right: auto;
		width:45%;
	}
	.slika2{
		width:68%;
		float:left;
		background:white;
	}
	.slika2 img{
		display: block;
		margin-left: auto;
		margin-right: auto;
		width:100%;
	}
	
	.registracija{
		width:100%;
		float:right;
		padding:0px 20px 15px 20px;
		background:white;
		clear:both;
		font-family:arial;
	}
	.button {
	  display: inline-block;
	  border-radius: 4px;
	  background-color: #4da6ff;
	  border: none;
	  color: white;
	  text-align: center;
	  font-size: 16px;
	  padding: 6px;
	  width: 180px;
	  transition: all 0.5s;
	  cursor: pointer;
	  margin: 5px;
	  float:right;
	}
	ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 100%;
    background-color:#4da6ff;
	border-top:2px solid black;
	border-bottom:2px solid black;
	float:left;
	}
	
	li {
		float:left;
		width:25%;
	}

	li a {
		display: block;
		color: white;
		padding: 15px 0px;
		text-decoration: none;
		width:100%;
		text-align: center;
		font-size: 18px;
		font-family:Arial;
	}

	li a.active {
		background-color: white;
		color: #4da6ff;
	}

	li a:hover:not(.active) {
		color: #4da6ff;
		background-color:white;
	}
	main{
		width:100%;
		clear:both;
		background:white;
		padding:20px 0px;
	}
	mySlides {display:none;}
	.search{
		width:100%;
		clear:both;
		display: block;
		
		
	}
	.search img {
		width:35px;
		height:35px;
	}
	form{
	width:100%;
	}
	input{
	width:100%;
	}
</style>
<body>
	<div class="wrapper">
		<header>
			<div class="logo">
				<div class="slika1">
					<img src="novo1.jpg"/>
				</div>
				<div class="slika2">
					<img src="logo2.jpg"/>
				</div>
			</div>
			
			<div class="registracija">
				<a href="login.html"><input class="button" type="submit" value="Prijava" /></a>
				<a href="registracija.html"><input class="button" type="submit" value="Registracija" /></a>
			</div>
			
			<nav class="gornji">
				<ul>
					<li><a class="active" href="Kolibrici.html">PRETRAŽI</a></li>
					<li><a href="#">KNJIŽARE</a></li>
					<li><a href="#">KATEGORIJE</a></li>
					<li><a href="#">NOVO</a></li>
				</ul>
			</nav>
		</header>
		
		<article id="registracija">
				<?php
		if (($uspjesnaPrijava == true && $korisnikJeAdministrator == true)
			   || (isset($_SESSION['$imePrijavljenogKorisnika'])) && $_SESSION['$levelPrijavljenogKorisnika'] == 2) {
	$dbc = mysqli_connect('localhost', 'root', '', 'projektno')
					   or die('Neuspješno spajanje na bazu.');
				mysqli_close($dbc);
				}
			   
			   else if ($uspjesnaPrijava == true && $korisnikJeAdministrator == false) {
				echo '<p>Bok ' . $imeKorisnika . '! Uspješno ste prijavljeni, ali niste administrator.</p>';
			} else if (isset($_SESSION['$imePrijavljenogKorisnika']) && $_SESSION['$levelPrijavljenogKorisnika'] == 1) {
				echo '<p>Bok ' . $_SESSION['$imePrijavljenogKorisnika'] .
					 '! Uspješno ste prijavljeni, ali niste administrator.</p>';
			} else if ($uspjesnaPrijava == false) {
				echo '<p>Niste uspješno prijavljeni,
					  molimo vas da se <a href="login.html">prijavite</a>
					  ili <a href="registracija.html">registrirate</a>.</p>';
			}
			?>
	</div>
</body>
</html>