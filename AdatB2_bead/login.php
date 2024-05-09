<?php
session_start();
/*
0 - user not found
1 - username required
2 - password required
*/
include "adatok.php";
$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
$kerdes="select * from users where username='".$_POST['user']."' and password='".$_POST['password']."'";
$eredmeny=pg_query($db,$kerdes);
$hibak=[];
if(isset($_POST['user']) || isset($_POST['password'])){
	if(strlen(trim($_POST['user']))==0){
		$hibak[]=1;
	}
	if(strlen(trim($_POST['password']))==0){
		$hibak[]=2;
	}
	if(count($hibak)==0){
		$sor=pg_num_rows($eredmeny);
		if($sor==0){
			$hibak[]=0;
		}else{
			$_SESSION['user']=$_POST['user'];
			header("Location: ./index.php");
		}
	}
	
	
}
?>

<?php include "header.php"; ?>
<main>
<div class="box">
	<form action="" method="post">
		<?php
			if(in_array(0, $hibak)){
				echo "<span class='error'>Hibás felhasználónév vagy jelszó!</span><br>";
			}
		?>
		<label for="user">Azonosító:</label>
		<input type="text" name="user">
		<br>
		<?php
			if(in_array(1, $hibak)){
				echo "<span class='error'>A mező kitöltése kötelező!</span>";
			}
		?>
		<br>
		<label for="password">Jelszó:</label>
		<input type="password" name="password">
		<br>
		<?php
			if(in_array(2, $hibak)){
				echo "<span class='error'>A mező kitöltése kötelező!</span>";
			}
		?>
		<br>
	<input type="submit" value="Belépés">
</form>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>