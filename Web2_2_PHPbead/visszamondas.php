<?php
session_start();
include "header.php";

$index=0;
while($index<count($vetitesek) && ($vetitesek[$index]->id != $_GET['vetites'])){
	$index++;
}
$vetites=$vetitesek[$index];
$i=0;
while($i<count($vetites->foglalasok) && $vetites->foglalasok[$i][0]!=$_SESSION['user']->email){
	$i++;
}
$jegyek=(int)($vetitesek[$index]->foglalasok[$i][1]);
unset($van);

if(isset($_POST['OK'])){
	$i=0;
	while($i<count($vetites->foglalasok) && $vetites->foglalasok[$i][0]!=$_SESSION['user']->email){
		$i++;
	}
	unset($vetitesek[$index]->foglalasok[$i]);
	$vetitesek[$index]->foglalasok=array_values($vetitesek[$index]->foglalasok);
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
	<p>Terem: <?= $vetites->terem ?></p>
	<p>Jegyek száma: <?= $jegyek ?>db</p>
	<p>Ár (összesen): <?= $jegyek * $vetites->ar ?>Ft</p>
	</div>
	
	<div id="megerositogombok" style="text-align: center;">
	<form action="" method="post" style="display: inline-block;" novalidate>
		<?php
		foreach($_SESSION['jegyvasarlas'] as $key => $value){
			
			echo "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
		}
			echo "<input type='hidden' name='vetites' value='" . $_GET['vetites'] . "'>";
			
		?>
	<input type="hidden" name="OK" value="OK">
	<input type="submit" value="Megerősítés">
	</form>
		<?php
			echo "<form action='rendeleseim.php' method='post' style='display: inline-block;'>";
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