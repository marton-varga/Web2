<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
else if(isset($_SESSION['user']) && $_SESSION['user']!='admin'){
	header("Location: ./index.php");
}
include "adatok.php";
?>

<?php
$hibak = [];
/*
11 - user required
12 - user already exists
21 - name required
22 - name requires space character
31 - password required
32 - password validation required
33 - passwords don't match
41 - email required
42 - wrong email format: *@*.*
43 - email already exists
51 - post office does not exist
*/
function check_email_format($string){
	$position_of_at=0;
	while($position_of_at<strlen($string) && $string[$position_of_at]!='@'){
		$position_of_at++;
	}
	if($position_of_at==strlen($string)){
		return false;
	}else{
		$position_of_dot=$position_of_at;
		while($position_of_dot<strlen($string) && $string[$position_of_dot]!='.'){
			$position_of_dot++;
		}
		if($position_of_dot>=strlen($string)-1){
			return false;
		}
	}
return true;}

if(isset($_POST['user']) || isset($_POST['name']) || isset($_POST['password']) || isset($_POST['password2']) || isset($_POST['email'])){
	//user
	if(!isset($_POST['user']) || strlen(trim($_POST['user']))==0){
		$hibak[]=11;
	}else{
		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		$eredmeny=pg_query($db,"select username,email from users");
		$sor=pg_num_rows($eredmeny);
		$oszlop=pg_num_fields($eredmeny);
		pg_close($db);
		$i=0;
		while($i<$sor && $_POST['user']!=pg_fetch_result($eredmeny,$i,0)){
			$i++;
		}
		if($i<$sor){
			$hibak[]=12;
		}
	}
	//name
	if(!isset($_POST['name']) || strlen(trim($_POST['name']))==0){
		$hibak[]=21;
	}else{
		$i=0;
		while($i<strlen($_POST['name'])-1 && $_POST['name'][$i]!=' '){
			$i++;
		}
		if($i>=strlen($_POST['name'])-1){
			$hibak[]=22;
		}
	}
	//password
	if(!isset($_POST['password']) || strlen(trim($_POST['password']))==0){
		$hibak[]=31;
	}
	else if(!isset($_POST['password2']) || strlen(trim($_POST['password2']))==0){
		$hibak[]=32;
	}else if($_POST['password']!=$_POST['password2']){
		$hibak[]=33;
	}
	//email
	if(!isset($_POST['email']) || strlen(trim($_POST['email']))==0){
		$hibak[]=41;
	}else if(!check_email_format($_POST['email'])){
		$hibak[]=42;
	}else{
		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		$eredmeny=pg_query($db,"select username,email from users");
		$sor=pg_num_rows($eredmeny);
		$oszlop=pg_num_fields($eredmeny);
		pg_close($db);
		$i=0;
		while($i<$sor && $_POST['email']!=pg_fetch_result($eredmeny,$i,1)){
			$i++;
		}
		if($i<$sor){
			$hibak[]=43;
		}
	}
	//office
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,"select id,name from offices where id='".$_POST['office']."'");
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	pg_close($db);
	if($sor==0){
		$hibak[]=51;
	}
}
if(isset($_POST['user']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email']) && count($hibak)==0){
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	pg_query($db, "insert into users values('".$_POST['user']."','".$_POST['password']."',".$_POST['office'].",'".$_POST['name']."','".$_POST['email']."')");
	pg_close($db);
	header("Location: ./sikeres.php");
}
?>

<?php include "header.php"; ?>
<main>
<div class="box">
	<form action="" method="post">
		<label for="user">Azonosító:</label>
		<br>
		<input type="text" name="user" <?php if(isset($_POST['user'])){echo "value='".$_POST['user']."'";}?>>
		<br>
		<?php
			if(count($hibak)>0){
				if(in_array(11,$hibak)){
					echo "<span class='error'>A mező kitöltése kötelező!</span>";
				}else if(in_array(12,$hibak)){
					echo "<span class='error'>A felhasználónév már foglalt!</span>";
				}
			}
		?>
		<br>
		<label for="name">Név:</label>
		<br>
		<input type="text" name="name" <?php if(isset($_POST['name'])){echo "value='".$_POST['name']."'";}?>>
		<br>
		<?php
			if(count($hibak)>0){
				if(in_array(21,$hibak)){
					echo "<span class='error'>A mező kitöltése kötelező!</span>";
				}else if(in_array(22,$hibak)){
					echo "<span class='error'>A névnek tartalmaznia kell szóközt!</span>";
				}
			}
		?>
		<br>
		<label for="password">Jelszó:</label>
		<br>
		<input type="password" name="password">
		<br>
		<?php
			if(count($hibak)>0 && in_array(31,$hibak)){
				echo "<span class='error'>A mező kitöltése kötelező!</span>";
			}
		?>
		<br>
		<label for="password">Jelszó megerősítése:</label>
		<br>
		<input type="password" name="password2">
		<br>
		<?php
			if(count($hibak)>0){
				if(in_array(32,$hibak)){
					echo "<span class='error'>A mező kitöltése kötelező!</span>";
				}else if(in_array(33,$hibak)){
					echo "<span class='error'>A jelszavak nem egyeznek!</span>";
				}
			}
		?>
		<br>
		<label for="email">Email:</label>
		<br>
		<input type="text" name="email" <?php if(isset($_POST['email'])){echo "value='".$_POST['email']."'";}?>>
		<br>
		<?php
			if(count($hibak)>0){
				if(in_array(41,$hibak)){
					echo "<span class='error'>A mező kitöltése kötelező!</span>";
				}else if(in_array(42,$hibak)){
					echo "<span class='error'>Hibás email formátum!</span>";
				}else if(in_array(43,$hibak)){
					echo "<span class='error'>A megadott email címmel már regisztrált felhasználó!</span>";					
				}
			}
		?>
		<br>
		<label for="office">Posta:</label>
		<br>
		<select name="office">
		<?php
			$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
			$eredmeny=pg_query($db,"select id,name from offices");
			$sor=pg_num_rows($eredmeny);
			$oszlop=pg_num_fields($eredmeny);
			pg_close($db);
			for($i=0;$i<$sor;$i++){
				echo "<option value='". pg_fetch_result($eredmeny,$i,0) ."'";
				if(isset($_POST['office']) && $_POST['office']==pg_fetch_result($eredmeny,$i,0)){
					echo "selected";
				}
				echo ">". pg_fetch_result($eredmeny,$i,1) ."</option>";
			}
		?>
		</select>
		<br>
		<?php
			if(count($hibak)>0 && in_array(51,$hibak)){
				echo "<span class='error'>A megadott posta nem létezik!</span>";
			}
		?>
		<br>
	<input type="submit" value="Reisztráció">
</form>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>