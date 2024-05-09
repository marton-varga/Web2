<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
include_once("adatok.php");
if($_SESSION['user']==admin && isset($_POST['dij'])){
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,("select * from prices where id=".$_POST['dij']));
	$sor=pg_num_rows($eredmeny);
	if($sor!=1){
		die("Nem található a megadott szempont!");
	}
	if(!is_numeric($_POST['ar']) || $_POST['ar']<1){
		die("A megadott ár hibás!");
	}
	pg_query($db,"update prices set price=".$_POST['ar']."where id=".$_POST['dij']);
	pg_close($db);
	unset($_POST['dij']);
	unset($_POST['ar']);
}
?>

<?php include "header.php"; ?>
<main>
<h1>Díjak</h1>
<table>
<tr>
	<th>Szolgáltatás</th>
	<th>Ár</th>
	<?php
	if($_SESSION['user']=='admin'){
		echo "<th>Módosítás</th>";
	}
	?>
</tr>
<?php
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,("select * from prices order by id"));
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	pg_close($db);
for($i=0;$i<$sor;$i++){
	echo "<tr id='tr_".pg_fetch_result($eredmeny,$i,0)."'>";
	for($j=1;$j<$oszlop;$j++){
		echo "<td>";
		echo pg_fetch_result($eredmeny,$i,$j);
		echo "</td>";
	}
	if($_SESSION['user']=='admin'){
		echo "<td><button class='modifyButton' id='mod_".pg_fetch_result($eredmeny,$i,0)."'>Módosítás</button></td>";
	}
	echo "</tr>";
}
?>
</table>
<h2>Számítások</h2>
<p>Csomag: a csomag alapára + kilogrammonként a súlyszorzó.</p>
<p>Külföldi: az alapdíjakon felüli felár külföldi címzett esetén.</p>
</main>
<?php
if($_SESSION['user']=="admin"){
	echo "<script type='text/javascript' src='dijak.js'></script>";
}
?>
<script type="text/javascript" src="js.js"></script>
</body>
</html>