<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
include "adatok.php";
$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
$kerdes="select * from offices";
$eredmeny=pg_query($db,$kerdes);
$sor=pg_num_rows($eredmeny);
$oszlop=pg_num_fields($eredmeny);
pg_close($db);
?>

<?php include "header.php"; ?>
<main>
<h1>Posták</h1>
<?php
if($_SESSION['user']=="admin"){
	echo "<a href='ujposta.php'><button>Új posta felvétele</button></a>";
}
?>

<table>
<tr>
<th>Azonosító</th>
<th>Név</th>
<th>Cím</th>
<th>Rang</th>
<?php
	if($_SESSION['user']=='admin'){
		echo "<th>Módosítás</th>";
		echo "<th>Törlés</th>";
	}
?>
</tr>
<?php

$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
for($i=0;$i<$sor;$i++){
	echo '<tr>';
	for($j=0;$j<$oszlop;$j++){
		echo '<td>';
		if($j!=2){
			echo pg_fetch_result($eredmeny, $i, $j);
		}else{
			echo pg_fetch_result(pg_query($db,("select zipcode from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0) . " ";
			echo pg_fetch_result(pg_query($db,("select settlement from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0) . ", ";
			echo pg_fetch_result(pg_query($db,("select street from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0) . " ";
			echo pg_fetch_result(pg_query($db,("select number from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0) . ", ";
			echo pg_fetch_result(pg_query($db,("select county from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0) . ", ";
			echo pg_fetch_result(pg_query($db,("select country from addresses where id=".pg_fetch_result($eredmeny, $i, $j))),0,0);
		}
		echo '</td>';
	}
	if($_SESSION['user']=='admin'){
		echo "<td><a href='postamodositas.php'><button>Módosítás</button></a></td>";
		echo "<td><button onclick='nincskesz()'>Törlés</button></td>";
	}
	echo '</tr>';
}
pg_close($db);
?>
</table>


</main>
<script>
function nincskesz(){
	alert("Fejlesztés alatt...");
}
</script>
<script type="text/javascript" src="js.js"></script>
</body>
</html>