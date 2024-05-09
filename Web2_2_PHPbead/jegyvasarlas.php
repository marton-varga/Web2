<?php
session_start();
include "header.php";

$i=0;
$van=false;
function vane($v){
	if(!$v){
		http_response_code(404);
		echo "<p style='color: red;'>A film nem található</p>";
		die();
	}
}
while($i<count($vetitesek) && !$van){
	if($vetitesek[$i]->id == $_GET['vetites']){
		$vetites=$vetitesek[$i];
		$van=true;
	}
	$i++;
}
vane($van);
$i=0;
$van=false;
while($i<count($filmek) && !$van){
if($vetites->cim == $filmek[$i]->cim){
		$van=true;
		$film = $filmek[$i];
	}
	$i++;
}
vane($van);
unset($i);
unset($van);
?>

<main>
	<aside id="right">
	<h2 class="block">Jegyvásárlás</h2>
	<?php
			echo "<ul class='block'>";
			echo "<li>Vetítés időpontja: " . $vetites->datum . "</li>";
			echo "<li>Terem: " . $vetites->terem . "</li>";
			$sum=0;
			for($i=0;$i<count($vetites->foglalasok);$i++){
				$sum += (int)($vetites->foglalasok[$i][1]);
			}
			$sum=$vetites->helyek-$sum;
			echo "<li>Szabad helyek: " . $sum . "</li>";
			echo "<li>Összes hely: " . $vetites->helyek . "</li>";
			echo "<li>Jegyár: " . $vetites->ar . " Ft</li>";
			echo "</ul>";
	?>
	
	<?php
		$hibak=["A mező kitöltése kötelező", "Hibás formátum", "a feltételek elfogadása kötelező"];
		$hiba=["nev"=>[],"email"=>[],"jegyek"=>[],"elfogad"=>[]];
		if(isset($_POST['nev']) || isset($_POST['email']) || isset($_POST['jegyek']) || isset($_POST['elfogad'])){
			//nev
			if((!isset($_POST['nev']) || $_POST['nev']=="")){array_push($hiba['nev'],$hibak[0]);}
			elseif(count(explode(" ",$_POST['nev']))<2){array_push($hiba['nev'],$hibak[1]);}
			//email
			if($_POST['email']==""){array_push($hiba['email'],$hibak[0]);}
			elseif((strpos($_POST['email'], "@")==false) || (strpos($_POST['email'], ".")==false)){array_push($hiba['email'], $hibak[1]);}
			elseif(strpos($_POST['email'], "@")>strrpos($_POST['email'], ".")){array_push($hiba['email'], $hibak[1]);}
			//jegyek
			if($_POST['jegyek']==""){array_push($hiba['jegyek'],$hibak[0]);}
			if(!is_numeric($_POST['jegyek'])){array_push($hiba['jegyek'],$hibak[1]);}
			//elfogad
			if(!isset($_POST['elfogad']) || $_POST['elfogad']!="on"){array_push($hiba['elfogad'],$hibak[2]);}
		}
		function hibakiir($str){
		global $hiba;
		if(count($hiba[$str])>0){
			echo "<ul'>";
			foreach($hiba[$str] as $h){
				echo "<li class='hiba'>" . $h . "</li>";
			}
			echo "</ul>";
		}
	}
	
	
	$van=false;
		if(isset($_POST['nev']) || isset($_POST['telefon']) || isset($_POST['email']) || isset($_POST['jelszo']) || isset($_POST['jelszomegerosit'])){
			$i=0;
			foreach($hiba as $h){
				if(count($h)>0){$van=true;break;}
			}
			if(!$van && !isset($_SESSION['vetites'])){
				$_SESSION['jegyvasarlas']=$_POST;
				$_SESSION['vetites']=$_GET['vetites'];
				header("Location: vasarlasjovahagyas.php");
				exit();
			}
			else{
				unset($_SESSION['vetites']);
			}
		}
	
	?>
	
	
	
	
	
	<form class="block" action="" method="post" novalidate>
	<label class="inplbl" for="nev">Név:</label>
	<input class="inp" type="text" name="nev" value=
		<?php
			echo "'";
			if(isset($_POST['nev'])){echo $_POST['nev'];}
			elseif(isset($_SESSION['user']) && $_SESSION['user']!=""){echo $_SESSION['user']->nev;}
			echo "'";
		?> >
	<?php hibakiir('nev'); ?>
		<br>
	<label class="inplbl" for="email">Email:</label>
	<input class="inp" type="text" name="email" value=
		<?php
			echo "'";
			if(isset($_POST['email'])){echo $_POST['email'];}
			elseif(isset($_SESSION['user']) && $_SESSION['user']!=""){echo $_SESSION['user']->email;}
			echo "'";
		?>>
	<?php hibakiir('email'); ?>
		<br>
	<label class="inplbl" for="jegydb">Jegyek száma:</label>
	<input class="inp" type="number" onKeyDown="return false" name="jegyek" value="<?php
		if(isset($_POST['jegyek'])){echo $_POST['jegyek'];}
		?>" min="1" max="<?= $sum ?>" >
	<?php hibakiir('jegyek'); ?>
		<br>
	<input type="checkbox" name="elfogad">
	<label for="elfogad">A <a href="feltetel.txt">felhasználói feltételeket</a> minden körülmények között elfogadom.</label>
		<br>
	<?php hibakiir('elfogad'); ?>
	<input type="submit" value="Küldés">
	</form>
	</aside>
	
	<aside id="left">
	<h2 class="block"><?=$film->cim ?></h2>
	<p class="block"><?=$film->leiras ?><br><a href=<?= $film->link ?>><?= $film->link ?></a></p>
	</aside>
	
	<?php
		if(isset($_SESSION['user']) && $_SESSION['user']->email=="admin@mozi.hu"){
			echo "<aside id='kikvettek'><div class='block'>";
			echo "<table><caption>A vetítésre vett jegyek</caption>";
			echo "<tr><th>Név</td><th>Email</td><th>Jegyek(db)</td></tr>";
			foreach($vetites->foglalasok as $f){
				echo "<tr>";
				$nev="";
				$users=json_decode(file_get_contents("users.txt"));
				$nonusers=json_decode(file_get_contents("nonusers.txt"));
				$i=0;
				while($i<count($users) && $users[$i]->email != $f[0]){
					$i++;}
				if($i<count($users)){$nev=$users[$i]->nev;}
				else{
					$i=0;
					while($i<count($nonusers) && $nonusers[$i]->email != $f[0]){$i++;}
					if($i<count($nonusers)){$nev=$nonusers[$i]->nev;}
				}
								
				echo "<td>";
				echo $nev;
				echo "</td>";
				
				
				echo "<td>";
				echo $f[0];
				echo "</td>";
				echo "<td>";
				echo $f[1];
				echo "</td>";				
				echo "</tr>";
			}
			
			echo "</table>";
			echo "</div></aside>";
		}
	?>
</main>

</body></html>