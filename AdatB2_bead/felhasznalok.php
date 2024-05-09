<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}else if(isset($_SESSION['user']) && $_SESSION['user']!='admin'){
	header("Location: ./index.php");
}
$deletingUser = ($_SESSION['user']=='admin' && isset($_POST['deleteUser']));
?>

<?php include "header.php"; ?>
<div class="messageShader" id="confirmDelete" <?php if(!$deletingUser) echo "hidden"?>>
<?php
include "adatok.php";
if($deletingUser){
	echo "<div class='message'>";
	if($_POST['deleteUser']=='admin'){
			echo "<p>Az admin fiók nem törölhető!</p>";
			echo "<button type='button' onclick='closeDialog()'>OK</button>";		
	}else{
		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		$eredmeny=pg_query($db,("select * from users where username='".$_POST['deleteUser']."'"));
		$sor=pg_num_rows($eredmeny);
		if($sor==1){
			$eredmeny=pg_query($db,("delete from users where username='".$_POST['deleteUser']."'"));
			echo "<p>A felhasználó sikeresen törölve!</p>";
			echo "<button type='button' onclick='closeDialog()'>OK</button>";
		}else{
			echo "<p>A felhasználó nem található!</p>";
			echo "<button type='button' onclick='closeDialog()'>OK</button>";
		}
		pg_close($db);
	}
	echo "</div>";
}
?>
</div>


<main>
<h1>Felhasználók</h1>
<div>
<a href="./ujfelhasznalo.php"><button>Új alkalmazott reisztrálása</button></a>
<form action="" method="post">
<select name="office">
<?php
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,"select id,name from offices");
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	pg_close($db);
	echo "<option value='all' ";
	if(!(isset($_POST['office'])) || (isset($_POST['office']) && $_POST['office']=='null')){echo "selected";}
	echo ">mind</option>";
	for($i=0;$i<$sor;$i++){
		echo "<option value='". pg_fetch_result($eredmeny,$i,0) ."'";
		if(isset($_POST['office']) && $_POST['office']==pg_fetch_result($eredmeny,$i,0)){
			echo "selected";
		}
		echo ">". pg_fetch_result($eredmeny,$i,0)." - ".pg_fetch_result($eredmeny,$i,1) ."</option>";
	}
?>
</select>
<input type="submit" value="Szűrés">
</form>
<table>
<tr>
<td>Azonosító</td>
<td>Posta</td>
<td>Név</td>
<td>Email</td>
<td>Módosítás</td>
<td>Törlés</td>
</tr>
<?php
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select username,locationId,name,email from users";
	if(isset($_POST['office'])){
		$eredmeny=pg_query($db,"select * from offices where id='".$_POST['office']."'");
		$sor=pg_num_rows($eredmeny);
		if($sor!=1 && $_POST['office']!='all'){
			die("A posta nem található!");
		}else if($_POST['office']!='all'){
			$kerdes.=" where locationId='".$_POST['office']."'";
		}
	}
	
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	for($i=0;$i<$sor;$i++){
		echo "<tr>";
		for($j=0;$j<$oszlop;$j++){
			echo "<td>";
				if($j!=1){
					echo pg_fetch_result($eredmeny,$i,$j);
				}else{
					echo pg_fetch_result(pg_query($db,"select name from offices where id='" . pg_fetch_result($eredmeny,$i,$j) . "'"),0,0);
				}
			echo "</td>";
		}
		echo "<td><a href='felhasznalomodositas.php?user=".pg_fetch_result($eredmeny,$i,0)."'><button>Módosítás</button></a></td>";
		if(pg_fetch_result($eredmeny,$i,0)=='admin'){
			echo "<td><button disabled>Törlés</button></td>";
		}else{
			echo "<td><button onclick='deleteUser(\"".pg_fetch_result($eredmeny,$i,0)."\")' id='delete_".pg_fetch_result($eredmeny,$i,0)."'>Törlés</button></td>";
		}
		echo "</tr>";
	}
?>
</table>
</div>
</main>

<script>
function deleteUser(a){
	document.getElementById("confirmDelete").innerHTML="<div class='message'><p>Biztosan törli a \""+a+"\" nevű felhasználót?</p><form action='' method='post'><input type='hidden' name='deleteUser' value='"+a+"' id='deleteUser'><input type='submit' value='Igen'><button type='button' onclick='closeDialog()'>Mégsem</button></form></div>";
	document.getElementById("confirmDelete").hidden=false;
}
function closeDialog(){
	document.getElementById("confirmDelete").hidden = true;	
}
</script>
<script type="text/javascript" src="js.js"></script>
</body>
</html>