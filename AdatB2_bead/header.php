<?php
	if(isset($_POST['logOut']) && $_POST['logOut']==true){
		session_destroy();
		header("Location: ./login.php");
	}
	$site = substr($_SERVER['REQUEST_URI'], 9);
?> 
<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
<link rel="stylesheet" type="text/css" href="CSS.css">
</head>
<body>
<header>
<div id='userName'>
<?php
if(isset($_SESSION['user']) && $_SESSION['user']!=null){
	echo "<span>".$_SESSION["user"]."</span>";
	echo "<form action='' method='post'><input type='hidden' name='logOut' value='true'><input type='submit' value='Kilépés'></form>";
} ?>
</div>
<nav>
<ul>
<li><a <?php if($site=="index.php") echo "class='current' ";?>href="index.php">Küldemények</a></li>
<li><a <?php if($site=="postak.php") echo "class='current' ";?>href='postak.php'>Posták</a></li>
<li><a <?php if($site=="dijak.php") echo "class='current' ";?>href='dijak.php'>Díjak</a></li>
<?php
if($_SESSION['user']=="admin"){
	echo "<li><a ";
	if($site=="felhasznalok.php"){
		echo "class='current' ";
	}
	echo "href='felhasznalok.php'>Felhasználók</a></li>";
	}
?>
</ul>
</nav>
</header>