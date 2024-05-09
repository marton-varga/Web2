<?php
session_start();
include "header.php";

$i=0;
while($i<count($vetitesek) && $vetitesek[$i]->id != $_GET['vetites']){$i++;}
$vetites=$vetitesek[$i];
?>

<main>
<div class="loginbox" id="siker">
	<div>
		<?php
		if(isset($_POST['confirm'])){
			unset($vetitesek[$i]);
			$vetitesek=array_values($vetitesek);
			file_put_contents("vetitesek.txt", json_encode($vetitesek));
		?>
		<p>A vetítés sikeresen törölve!</p>
		<a href="index.php"><button>Vissza a kezdőlapra</button></a>
		<?php }else{
		?>
		<p>Biztosan törölni szeretné a filmet?</p>
		<ul>
		<li>Cím: <?= $vetites->cim ?></li>
		<li>Vetítés ideje: <?= $vetites->datum ?></li>
		<li>Terem: <?= $vetites->terem ?></li>
		<li>Nyelv: <?= $vetites->nyelv ?></li>
		</ul>
		<form style="display: inline-block;" action="" method="post">
		<input type="hidden" name="confirm" value="confirm">
		<input type="submit" class="btn" value="Törlés">
		</form>
		<form action="vetitesmodositas.php" method="get" style="display: inline-block;">
		<input type="hidden" name="vetites" value=<?= $vetites->id ?>>
		<input type="submit" class="btn" value="Mégsem">
		</form>
		<?php } ?>
	</div>
</div>
</main>
</body>
</html>