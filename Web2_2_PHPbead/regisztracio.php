<?php
session_start();
include "header.php";

class User{
	var $email;
	var $jelszo;
	var $nev;
	var $telefon;
	var $jegyek;
	function __construct($e_,$p_,$n_,$t_,$j_=[]){
		$this->email=$e_;
		$this->jelszo=$p_;
		$this->nev=$n_;
		$this->telefon=$t_;
		$this->jegyek=$j_;
	}
}
?>

<?php
	$hibak=["A mező kitöltése kötelező", "Hibás formátum", "Már regisztrált felhasználó a címről", "A jelszavak nem egyeznek"];
	$hiba=["nev"=>[],"telefon"=>[],"email"=>[],"jelszo"=>[]];
	if(isset($_POST['nev']) || isset($_POST['telefon']) || isset($_POST['email']) || isset($_POST['jelszo']) || isset($_POST['jelszomegerosit'])){
		//nev
		if($_POST['nev']==""){array_push($hiba['nev'],$hibak[0]);}
		elseif(count(explode(" ",$_POST['nev']))<2){array_push($hiba['nev'],$hibak[1]);}
		//telefon
		if($_POST['telefon']==""){array_push($hiba['telefon'],$hibak[0]);}
		elseif(strlen($_POST['telefon'])!=9){array_push($hiba['telefon'],$hibak[1]);}
		elseif(!is_numeric($_POST['telefon'])){array_push($hiba['telefon'],$hibak[1]);}
		//email
		if($_POST['email']==""){array_push($hiba['email'],$hibak[0]);}
		elseif((strpos($_POST['email'], "@")==false) || (strpos($_POST['email'], ".")==false)){array_push($hiba['email'], $hibak[1]);}
		elseif(strpos($_POST['email'], "@")>strrpos($_POST['email'], ".")){array_push($hiba['email'], $hibak[1]);}
		else{
			$users=json_decode(file_get_contents("users.txt"));
			$i=0;
			while($i<count($users) && ($users[$i]->email != $_POST["email"])){
				$i++;
			}
			if($i<count($users)){array_push($hiba['email'], $hibak[2]);}
			unset($i);
			unset($users);
		}
		//jelszo
		if($_POST['jelszo']=="" || $_POST['jelszomegerosit']==""){array_push($hiba['jelszo'],$hibak[0]);}
		elseif($_POST['jelszo']!==$_POST['jelszomegerosit']){array_push($hiba['jelszo'],$hibak[3]);}
	}
	
	function hibakiir($str){
		global $hiba;
		if(count($hiba[$str])>0){
			echo "<ul>";
			foreach($hiba[$str] as $h){
				echo "<li>" . $h . "</li>";
			}
			echo "</ul>";
		}
	}
	
?>
<main>
<div class="loginbox">
	<h2>Regisztráció</h2>
	<?php
		$van=false;
		if(isset($_POST['nev']) || isset($_POST['telefon']) || isset($_POST['email']) || isset($_POST['jelszo']) || isset($_POST['jelszomegerosit'])){
			$i=0;
			foreach($hiba as $h){
				if(count($h)>0){$van=true;break;}
			}
			if(!$van){
				$felhasznalo = new User($_POST['email'],$_POST['jelszo'],$_POST['nev'],$_POST['telefon']);
					$nonusers=json_decode(file_get_contents("nonusers.txt"));
					$i=0;
					while($i<count($nonusers) && $nonusers[$i]->email!=$_POST['email']){$i++;}
					if($i<count($nonusers)){
						unset($nonusers[$i]);
						file_put_contents("nonusers.txt", json_encode($nonusers));
					}
					unset($nonusers);
					unset($i);
				$users=json_decode(file_get_contents("users.txt"));
				$users[]=$felhasznalo;
				file_put_contents("users.txt", json_encode($users));
				$_SESSION['user']=json_decode(json_encode($felhasznalo));
				
				
					
				header("Location: index.php");
				exit();
			}
		}
	?>
	<form action="" method="post" novalidate>
		<label for="nev">Teljes név:</label>
		<input type="text" name="nev" value="<?php if(isset($_POST['nev'])){echo $_POST['nev'];} ?>">
		<br>
			<?php hibakiir('nev'); ?>
		<label for="telefon">Telefonszám:</label>
		<input type="text" name="telefon" value="<?php if(isset($_POST['telefon'])){echo $_POST['telefon'];} ?>">
		<br>
			<?php hibakiir('telefon'); ?>
		<label for="email">Email:</label>
		<input type="text" name="email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
		<br>
			<?php hibakiir('email'); ?>
		<label for="jelszo">Jelszó:</label>
		<input type="password" name="jelszo" value="<?php if(isset($_POST['jelszo'])){echo $_POST['jelszo'];} ?>">
		<br>
			<?php hibakiir('jelszo'); ?>
		<label for="jelszomegerosit">Jelszó megerősítése:</label>
		<input type="password" name="jelszomegerosit" value="<?php if(isset($_POST['jelszomegerosit'])){echo $_POST['jelszomegerosit'];} ?>">
		<br>
	<input type="submit" value="Regisztráció">
	<input type="button" value="Mégsem" onclick="cancel()">
	</form>
</div>
</main>
<script>
function cancel(){
	window.location.href="index.php";
}
</script>
</body>
</html>