<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
else if(isset($_SESSION['user']) && $_SESSION['user']!='admin'){
	header("Location: ./index.php");
}
else if(!isset($_GET['user']) || $_SESSION['user'] != 'admin'){
	die("Az felhasználó nem elérhető.");
}
include "adatok.php";
$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
$uEredmeny=pg_query($db,"select * from users where username='".$_GET['user']."'");
$uSor=pg_num_rows($uEredmeny);
$uOszlop=pg_num_fields($uEredmeny);
pg_close($db);
if($uSor!=1){
	die("Az felhasználó nem elérhető.");	
}
?>

<?php
$hibak = [];
/*
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
$modify_line="update users set ";
$modify="";
if(isset($_POST['user']) || isset($_POST['name']) || isset($_POST['password']) || isset($_POST['password2']) || isset($_POST['email'])){
	//name
	if(isset($_POST['name']) && strlen(trim($_POST['name']))>0){
		$i=0;
		while($i<strlen($_POST['name'])-1 && $_POST['name'][$i]!=' '){
			$i++;
		}
		if($i>=strlen($_POST['name'])-1){
			$hibak[]=22;
		}
	}
	if(count($hibak)==0 && (isset($_POST['name']) && strlen($_POST['name'])>0)){
		$modify="name='".$_POST['name']."'";
		$modify_line.=$modify;
	}
	//password
	if(isset($_POST['password2']) && strlen(trim($_POST['password2']))>0){
		if(!isset($_POST['password']) || strlen(trim($_POST['password']))==0){
			$hibak[]=31;
		}
	}else if(isset($_POST['password']) && strlen(trim($_POST['password']))>0){
		if(!isset($_POST['password2']) || strlen(trim($_POST['password2']))==0){
			$hibak[]=32;
		}
	}
	if($_POST['password']!=$_POST['password2']){
		$hibak[]=33;
	}
	if(count($hibak)==0){
		if(isset($_POST['password']) && strlen($_POST['password'])>0){
			if(strlen($modify)!=0)$modify=",";
			$modify.="password='".$_POST['password']."'";
			$modify_line.=$modify;
		}
	}
	//email
	if(isset($_POST['email']) && strlen(trim($_POST['email']))>0){
		if(!check_email_format($_POST['email'])){
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
			if($i<$sor && pg_fetch_result($eredmeny,$i,1)!=pg_fetch_result($uEredmeny,0,4)){
				$hibak[]=43;
			}
		}
	}
	if(count($hibak)==0){
		if(isset($_POST['email']) && strlen($_POST['email'])>0){
			if(strlen($modify)!=0)$modify=",";
			$modify.="email='".$_POST['email']."'";
			$modify_line.=$modify;
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
	if(count($hibak)==0){
		if(isset($_POST['office']) && strlen($_POST['office'])>0){
			if(strlen($modify)!=0)$modify=",";
			$modify.="locationId=".$_POST['office']."";
			$modify_line.=$modify;
		}
		$modify_line.=" where username='".$_GET['user']."'";
		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		pg_query($db, $modify_line);
		pg_close($db);
		header("Location: ./sikeres.php");
	}
}
?>

<?php include "header.php"; ?>
<main>
<div class="box">
	<form action="" method="post">
		<span>Azonosító: <br><?php echo pg_fetch_result($uEredmeny,0,0); ?></span>
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
		<input type="text" name="name" <?php if(isset($_POST['name'])){echo "value='".$_POST['name']."'";}else{echo "value='".pg_fetch_result($uEredmeny,0,3)."'";}?>>
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
		<label for="password">Új jelszó:</label>
		<br>
		<input type="password" name="password">
		<br>
		<?php
			if(count($hibak)>0 && in_array(31,$hibak)){
				echo "<span class='error'>Jelszóváltoztatás esetén mindkét mező kitöltése kötelező!</span>";
			}
		?>
		<br>
		<label for="password">Új jelszó megerősítése:</label>
		<br>
		<input type="password" name="password2">
		<br>
		<?php
			if(count($hibak)>0){
				if(in_array(32,$hibak)){
					echo "<span class='error'>Jelszóváltoztatás esetén mindkét mező kitöltése kötelező!</span>";
				}else if(in_array(33,$hibak)){
					echo "<span class='error'>A jelszavak nem egyeznek!</span>";
				}
			}
		?>
		<br>
		<label for="email">Email:</label>
		<br>
		<input type="text" name="email" <?php if(isset($_POST['email'])){echo "value='".$_POST['email']."'";}else{echo "value='".pg_fetch_result($uEredmeny,0,4)."'";}?>>
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
				if((isset($_POST['office']) && $_POST['office']==pg_fetch_result($eredmeny,$i,0)) || (!isset($_POST['office']) && pg_fetch_result($eredmeny,$i,0)==pg_fetch_result($uEredmeny,0,2))){
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
	<input type="submit" value="Mentés">
</form>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>