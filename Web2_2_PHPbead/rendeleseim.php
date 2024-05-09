<?php
session_start();
include "header.php";
if(isset($_SESSION['vetites'])){unset($_SESSION['vetites']);}
$vetitesek=json_decode(file_get_contents("vetitesek.txt"));

?>

<main id="indexmain">
<h2>Rendeléseim</h2>

<?php
	
?>

<table id="rendelestabla">
<?php
	$now = new DateTime('now');
	$now->modify('-1 day');
	echo "<tr>";
	echo "<th>Film</th>";
	echo "<th>Dátum</th>";
	echo "<th>Terem</th>";
	echo "<th>Jegyek</th>";
	echo "<th>Lemondás</th>";
	echo "</tr>";
	foreach($vetitesek as $v){
		foreach($v->foglalasok as $r){
			if($r[0]==$_SESSION['user']->email){
				echo "<tr>";
				echo "<td>";
				echo $v->cim;
				echo "</td>";
				echo "<td>";
				echo $v->datum;
				echo "</td>";
				echo "<td>";
				echo $v->terem;
				echo "</td>";
				echo "<td>";
				echo $r[1];
				echo "</td>";
				$Vdate=date_create_from_format("Y-m-d H:i", $v->datum);
				echo "<td>";
				if($Vdate > $now){
					echo "<a href='visszamondas.php?vetites=". $v->id ."'><button>Lemondás</button></a>";
				}
				echo "</td>";
				echo "</tr>";
			}
		}
	}

?>
</table>
</main>

<script>
	date = new Date(date.getTime()-(24*60*60*1000));
</script>
</body></html>