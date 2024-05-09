<?php
session_start();
include "header.php";

class Nonuser{
	var $nev;
	var $email;
	function __construct($n_,$e_){
		$this->nev=$n_;
		$this->email=$e_;
	}
};

$index=0;
$van=false;
while($index<count($vetitesek) && !$van){
	if($vetitesek[$index]->id == $_SESSION['vetites']){
		$vetites=$vetitesek[$index];
		$van=true;
	}
	$index++;
}
unset($van);

if(isset($_POST['OK'])){
	
	$users=json_decode(file_get_contents("users.txt"));
	$i=0;
	while($i<count($users) && $users[$i]->email!=$_POST['email']){$i++;}
	if($i==count($users)){
		$i=0;
		$nonusers=json_decode(file_get_contents("nonusers.txt"));
		while($i<count($nonusers) && $nonusers[$i]->email!=$_POST['email']){$i++;}
		if($i==count($nonusers)){
			$nonuser=new Nonuser($_POST['nev'], $_POST['email']);
			$nonusers[]=$nonuser;
			file_put_contents("nonusers.txt", json_encode($nonusers));
		}
	}
	$i=0;
	while($i<count($vetites->foglalasok) && $vetites->foglalasok[$i][0]!=$_SESSION['jegyvasarlas']['email']){
		//echo $_SESSION['jegyvasarlas']['email'];
		//echo "==" . $vetites->foglalasok[$i][0];
		$i++;
	}
	if($i<count($vetites->foglalasok)){
		$vetites->foglalasok[$i][1]=(int)($vetites->foglalasok[$i][1])+$_POST['jegyek'];
	}else{
		$vetites->foglalasok[]=[$_POST['email'], $_POST['jegyek']];
	}
		$vetitesek=json_decode(file_get_contents("vetitesek.txt"));
		
		$i=0;
		while($i<count($vetitesek) && $vetitesek[$i]->id!=$_SESSION['vetites']){
			$i++;
		}
		unset($vetitesek[$i]);
		$vetitesek=array_values($vetitesek);
		$vetites->id=$_SESSION['vetites'];
		$vetitesek[]=$vetites;
		
		//$vetitesek[$index-1]=$vetites;
	file_put_contents("vetitesek.txt", json_encode($vetitesek));
	header("Location: sikeres.php");
	exit();
}
?>


<main>
<div class="loginbox">
	<h2>Megerősítés</h2>
	
	<?php	
	
	?>
	
	<div>
	<p>Film: <?= $vetites->cim ?></p>
	<p>Vetítés időpontja: <?= $vetites->datum ?></p>
	<p>Név: <?= $_SESSION['jegyvasarlas']['nev'] ?></p>
	<p>Email: <?= $_SESSION['jegyvasarlas']['email'] ?></p>
	<?php
		$fizetendo = $_SESSION['jegyvasarlas']['jegyek'] * $vetites->ar;
	?>
	<p>Jegyek száma: <?= $_SESSION['jegyvasarlas']['jegyek'] ?>db</p>
	<p>Ár (összesen): <?= $fizetendo ?>Ft</p>
	</div>
	
	<div id="megerositogombok" style="text-align: center;">
	<form action="" method="post" style="display: inline-block;" novalidate>
		<?php
		foreach($_SESSION['jegyvasarlas'] as $key => $value){
			
			echo "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
		}
			echo "<input type='hidden' name='vetites' value='" . $_SESSION['vetites'] . "'>";
			
		?>
	<input type="hidden" name="OK" value="OK">
	<input type="submit" value="Megerősítés">
	</form>
		<?php
			echo "<form action='jegyvasarlas.php?vetites='". $_SESSION['vetites'] ."' method='post' style='display: inline-block;'>";
			foreach($_SESSION['jegyvasarlas'] as $key => $value){
				echo "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
			}
		?>
		
	<input type="submit" value="Mégsem">
	</form>
	</div>
</div>
</main>
<script>
</script>
</body>
</html>