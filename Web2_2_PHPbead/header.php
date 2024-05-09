<?php
$vetitesek=json_decode(file_get_contents("vetitesek.txt"));
$filmek=json_decode(file_get_contents("filmek.txt"));
function cmp($a, $b){
	$dateA=date_create_from_format("Y-m-d H:i", $a->datum);
	$dateB=date_create_from_format("Y-m-d H:i", $b->datum);
	
	if ($dateA == $dateB) return 0;
	elseif($dateA > $dateB) return 1;
	else return -1;
}
if(count($vetitesek)>0)usort($vetitesek, 'cmp');

if(isset($_POST['logout'])){unset($_SESSION['user']);}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset ="UTF-8">
<link rel="stylesheet" type="text/css" href="CSS.css">
<link rel="icon" href="logo.png" type="image/x-icon">
<title>Mozi</title>
</head><body>

<header>
<nav>
<?php
	if(isset($_SESSION['user']) && $_SESSION['user']!=""){
		echo "<span>" . $_SESSION['user']->nev . " </span>";
	}
		echo "<span id='mail' hidden>";
		if(isset($_SESSION['user'])){echo $_SESSION['user']->email;}
		echo "</span>";
		if(isset($_SESSION['user']) && $_SESSION['user']!=""){
			if($_SESSION['user']->email=="admin@mozi.hu"){
				echo "<button id='ujvetites' onclick='ujvetites()'>Új vetítés felvétele</button>";
			}
		echo "<a href='rendeleseim.php'><button id='rendeleseim'>Rendeléseim</button></a>";
		echo "<form style='display: inline-block;' action='index.php' method='post' novalidate><input type='hidden' name='logout' value=''><input type='submit' value='Kijelentkezés'></form>";
		}
	else{
		echo "<button id='loginbtn' onclick='login()'>Bejelentkezés</button>";
		echo "<button id='regbtn' onclick='signup()'>Regisztráció</button>";
	}
?>
</nav>
<a href="index.php"><img id="logo" src="logo.png" alt="logo">
<h1>Mozi</h1></a>
</header>

<script>
	function login(){
		window.location.href="belepes.php";
	}
	function signup(){
		window.location.href="regisztracio.php";
	}
	function logout(){
		<?php
			$user="";
		?>
		location.reload();
	}
	//admin
	function ujvetites(){
		window.location.href="ujvetites.php";
	}
</script>
