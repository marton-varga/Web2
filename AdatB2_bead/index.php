<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
if(isset($_POST['select']) && !($_POST['select']=='in' || $_POST['select']=='into' || ($_SESSION['user']=='admin' && $_POST['select']=='del'))){
	die("Hibás érték!");
}
if(!isset($_POST['select'])){$_POST['select']='in';}
include_once "adatok.php";
$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
$eredmeny=pg_query($db,"select locationid from users where username='".$_SESSION['user']."'");
$sor=pg_num_rows($eredmeny);
if($sor!=1){die("Hibás érték!");}
if(!isset($_POST['office'])){$_POST['office']=pg_fetch_result($eredmeny,0,0);}
if(isset($_POST['office']) && $_POST['office']!='all'){
	$eredmeny=pg_query($db,"select id from offices where id=".$_POST['office']);
	if(pg_num_rows($eredmeny)!=1){
		die("Hibás érték!");
	}
pg_close($db);
}
function findAddressByPerson($i){
	$adatbazis='vtx73j';
	$user='vtx73j';
	$jelszo='vtx73j';
	$port=5432; /* port szám */
	$host='localhost';
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select addressid from persons where id=".$i;
	$eredmeny=pg_query($db,$kerdes);
	$num=pg_fetch_result($eredmeny,0,0);
	pg_close($db);
return $num;}
function echoAddress($i){
	$adatbazis='vtx73j';
	$user='vtx73j';
	$jelszo='vtx73j';
	$port=5432; /* port szám */
	$host='localhost';
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select zipcode from addresses where id=".$i;
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	$string="";
	$string.=pg_fetch_result(pg_query($db,("select zipcode from addresses where id=".$i)),0,0)." ";
	$string.=pg_fetch_result(pg_query($db,("select settlement from addresses where id=".$i)),0,0).", ";
	$string.=pg_fetch_result(pg_query($db,("select street from addresses where id=".$i)),0,0)." ";
	$string.=pg_fetch_result(pg_query($db,("select number from addresses where id=".$i)),0,0).", ";
	$string.=pg_fetch_result(pg_query($db,("select county from addresses where id=".$i)),0,0).", ";
	$string.=pg_fetch_result(pg_query($db,("select country from addresses where id=".$i)),0,0);
	pg_close($db);
return $string;}
?>

<?php
if(isset($_POST['confirmAction'])){
	if((isset($_POST['package_id']) && isset($_POST['office_post'])) && (is_numeric($_POST['package_id']) && is_numeric($_POST['office_post']))){
		if($_POST['confirmAction']=='tovabbit'){
			$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
			//package_id !valid
			$kerdes="select * from packages where id=".$_POST['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			$sor=pg_num_rows($eredmeny);			
			if($sor!=1){
				die("Hibás érték!");
			}else if(pg_fetch_result($eredmeny,0,5)=='f' || pg_fetch_result($eredmeny,0,6)=='t'){
			//package_id -> !arrived || delivered
				die("Hibás érték!");
			}
			//office_post valid-e
			$kerdes="select * from offices where id=".$_POST['office_post'];
			$eredmeny=pg_query($db,$kerdes);
			$sor=pg_num_rows($eredmeny);
			if($sor!=1){
				die("Hibás érték!");
			}
			//offid==office_post || !admin
			$kerdes="select locationid from users where username='".$_SESSION['user']."'";
			$eredmeny=pg_query($db,$kerdes);
			$offid=pg_fetch_result($eredmeny,0,0);
			$kerdes2="select currentlocation from packages where id=".$_POST['package_id'];
			$eredmeny2=pg_query($db,$kerdes2);
			if(pg_fetch_result($eredmeny2,0,0)==$_POST['office_post']){
			//package_id -> location!=office_post
				die("Hibás érték!");
			}
			if($_SESSION['user']!='admin' && $offid!=pg_fetch_result($eredmeny2,0,0)){
				die("Hibás érték!");
			}
			$kerdes="update packages set arrived='f',currentlocation=".$_POST['office_post']." where id=".$_POST['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			pg_close($db);
		}else{
			die("Hibás érték!");
		}
	}
}



$action = (isset($_POST['action']) && ($_POST['action']=='tovabbit' || $_POST['action']=='kiszallit' || $_POST['action']=='megerkezett' || $_POST['action']=='torles'));
?>



<?php include "header.php"; ?>
<div class="messageShader" id="confirmDelete" <?php if(!$action) echo "hidden"?>>
<?php
include "adatok.php";
if($action){
	echo "<div class='message'>";
	if(isset($_POST['package_id']) && is_numeric($_POST['package_id'])){
		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		$eredmeny=pg_query($db,("select * from packages where id=".$_POST['package_id']));
		$sor=pg_num_rows($eredmeny);
		if($sor!=1){
			pg_close($db);
			die("A küldemény nem található!");
		}
		if($_POST['action']=='tovabbit'){
			$kerdes2="select locationid from users where username='".$_SESSION['user']."'";
			$eredmeny2=pg_query($db,$kerdes2);
			$offid=pg_fetch_result($eredmeny2,0,0);

			$eredmeny2=pg_query($db,"select id,name from offices");
			$sor2=pg_num_rows($eredmeny2);
			
			$kerdes3="select currentlocation from packages where id=".$_POST['package_id'];
			$eredmeny3=pg_query($db,$kerdes3);
			$sor3=pg_num_rows($eredmeny3);			
			
			echo "<span>Továbbítás ide: </span>";
			echo "<form action='' method='post' style='display: inline;'>";
			echo "<select name='office_post'>";
			for($i=0;$i<$sor2;$i++){
				if((pg_fetch_result($eredmeny3,0,0)!=pg_fetch_result($eredmeny2,$i,0) && pg_fetch_result($eredmeny2,$i,0)!=$offid) || ($_SESSION['user']=='admin' && pg_fetch_result($eredmeny3,0,0)!=pg_fetch_result($eredmeny2,$i,0))){
					echo "<option value='". pg_fetch_result($eredmeny2,$i,0) ."'";
					if(isset($_POST['office_post']) && $_POST['office_post']==pg_fetch_result($eredmeny2,$i,0)){
						echo "selected";
					}
				}
				echo ">". pg_fetch_result($eredmeny2,$i,0)." - ".pg_fetch_result($eredmeny2,$i,1) ."</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='package_id' value='".$_POST['package_id']."'>";
			echo "<input type='hidden' name='confirmAction' value='tovabbit'>";
			echo "<br><br>";
			echo "<input type='submit' value='Továbbít'></form>";
			
			echo "<form action='' method='post' style='display: inline;'>";
			echo "<input type='submit' value='Mégsem'></form>";
		}else if($_POST['action']=='kiszallit'){
			//arrived, !delivered
			$kerdes="select arrived,delivered from packages where id=".$_SESSION['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			if(pg_fetch_result($eredmeny,0,0)=='f' || pg_fetch_result($eredmeny,0,1)=='t'){
				pg_close($db);
				die("Hibás érték!");
			}
			//location==offid || admin
			$kerdes="select locationid from users where username='".$_SESSION['user']."'";
			$eredmeny=pg_query($db,$kerdes);
			$offid=pg_fetch_result($eredmeny,0,0);
			$kerdes="select currentlocation from packages where id=".$_POST['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			if($_SESSION['user']!='admin' && pg_fetch_result($eredmeny,0,0)!=$offid){
				die("Hibás érték!");
			}
			
			$kerdes="update packages set delivered='t' where id=".$_POST['package_id'];
			pg_query($db,$kerdes);
			echo "<p>A küldemény kiszállítva.</p>";
			echo "<form action='' method='post'><input type='submit' value='OK'></form>";
		}else if($_POST['action']=='megerkezett'){
			//arrived, !delivered
			$kerdes="select arrived,delivered from packages where id=".$_SESSION['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			if(pg_fetch_result($eredmeny,0,0)=='t' || pg_fetch_result($eredmeny,0,1)=='t'){
				pg_close($db);
				die("Hibás érték!");
			}
			//location==offid || admin
			$kerdes="select locationid from users where username='".$_SESSION['user']."'";
			$eredmeny=pg_query($db,$kerdes);
			$offid=pg_fetch_result($eredmeny,0,0);
			$kerdes="select currentlocation from packages where id=".$_POST['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			if($_SESSION['user']!='admin' && pg_fetch_result($eredmeny,0,0)!=$offid){
				die("Hibás érték!");
			}
			
			$kerdes="update packages set arrived='t' where id=".$_POST['package_id'];
			pg_query($db,$kerdes);
			echo "<p>A küldemény fogadva.</p>";
			echo "<form action='' method='post'><input type='submit' value='OK'></form>";
		}else if($_POST['action']=='torles'){
			//package_id valid-e

			//package delivered
			$kerdes="select delivered from packages where id=".$_POST['package_id'];
			$eredmeny=pg_query($db,$kerdes);
			$sor=pg_num_rows($eredmeny);
			if($sor!=1 || pg_fetch_result($eredmeny,0,0)=='f'){
				die("Hibás érték!");
			}
			//admin-e
			if($_SESSION['user']!='admin'){
				die("Nincs jogosultsága törlésre!");
			}
			$kerdes="delete from packages where id=".$_POST['package_id'];
			pg_query($db,$kerdes);
			
			echo "<p>Itt még lenne egy megerősítés, de tőrlődött a küldemény...</p>";
			echo "<form action='' method='post'><input type='submit' value='OK'></form>";
		}

		pg_close($db);
	}else{
		die("Hibás érték!");
	}
	echo "</div>";
}
?>
</div>





<main>
<h1>Küldemények</h1>

<a href="./ujkuldemeny.php"><button>Új küldemény felvétele</button></a>
<br><br>
<form action="" method="post">
<input type="hidden" name="select" value='<?php echo $_POST['select'] ?>'>
<select name="office">
<?php
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,"select id,name from offices");
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	$eredmeny2=pg_query($db,"select locationid from users where username='".$_SESSION['user']."'");
	$sor2=pg_num_rows($eredmeny2);
	if(!isset($_POST['office']) && $sor2==1){
		$_POST['office']=pg_fetch_result($eredmeny2,0,0);
	}
	echo "<option value='all' ";
	if((isset($_POST['office'])) && ($_POST['office']=='all')){echo "selected";}
	echo ">mind</option>";
	for($i=0;$i<$sor;$i++){
		echo "<option value='". pg_fetch_result($eredmeny,$i,0) ."'";
		if(isset($_POST['office']) && $_POST['office']==pg_fetch_result($eredmeny,$i,0)){
			echo "selected";
		}
	echo ">". pg_fetch_result($eredmeny,$i,0)." - ".pg_fetch_result($eredmeny,$i,1) ."</option>";	}
	pg_close($db);
?>
</select>
<input type="submit" value="Szűrés">
</form>

<div class="flex-container">
	<div class="flex-item-<?php echo $_SESSION['user']=='admin'?"a":"u"; ?>">
	<form action="" method="post">
	<input type="hidden" name="select" value="in">
	<input type="hidden" name="office" value='<?php echo $_POST['office'] ?>'>
	<input type="submit" class='flex-button<?php echo $_POST['select']=='in'?"-post":""; ?>' value='Postán'>
	</form>
	</div>
	<div class="flex-item-<?php echo $_SESSION['user']=='admin'?"a":"u"; ?>">
	<form action="" method="post">
	<input type="hidden" name="select" value="into">
	<input type="hidden" name="office" value='<?php echo $_POST['office'] ?>'>
	<input type="submit" class='flex-button<?php echo $_POST['select']=='into'?"-post":""; ?>' value='Bejövő'>
	</form>
	</div>
	<?php
	if($_SESSION['user']=='admin'){
		echo "<div class='flex-item-a'><form action='' method='post'>";
		echo "<input type='hidden' name='select' value='del'>";
		echo "<input type='hidden' name='office' value='".$_POST['office']."'>";
		echo "<input type='submit' class='flex-button";
		if($_POST['select']=='del'){
			echo "-post";
		}
		echo "' value='Törölt'></form></div>";
	}
	?>
</div>
<br>

<table>
<tr>
<th>Azonosító</th>
<th>Feladás dátuma</th>
<th>Feladó neve</th>
<th>Címzett neve</th>
<th>Jelenlegi hely</th>
<th>Típus</th>

</tr>
<?php
	/*
	0-azon
	1-datum
	2-feladó
	3-címzett
	4-jelen
	5-megérkezett
	6-kiszállítva
	7-fizetve
	8-típus
	9-elsőbbségi
	10-ajánlott
	11-súly
	*/
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select * from packages";
	if(isset($_POST['office'])){
		if($_POST['office']!='all'){
			$kerdes="select * from packages where currentlocation=".$_POST['office'];
		}
	}
	if($_SESSION['user']=='admin' && $_POST['select']=='del'){
		
		if(!strpos($kerdes,"where")){
			$kerdes.=" where ";
		}else{
			$kerdes.=" and ";
		}
		$kerdes.="delivered=true";
	}else if($_POST['select']=='in'){
		if(!strpos($kerdes,"where")){
			$kerdes.=" where ";
		}else{
			$kerdes.=" and ";
		}
		$kerdes.="arrived=true and delivered=false";
	}else if($_POST['select']=='into'){
		if(!strpos($kerdes,"where")){
			$kerdes.=" where ";
		}else{
			$kerdes.=" and ";
		}
		$kerdes.="arrived=false and delivered=false";
	}	
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	$oszlop=pg_num_fields($eredmeny);
	for($i=0;$i<$sor;$i++){
		echo "<tr class='main_row' id='row_".pg_fetch_result($eredmeny,$i,0)."_a'>";
			$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
			echo "<td>";
			echo pg_fetch_result($eredmeny,$i,0);
			echo "</td>";
			echo "<td>";
			echo pg_fetch_result($eredmeny,$i,1);
			echo "</td>";
			echo "<td>";
			$eredmeny2=pg_query($db,"select name from persons where id=".pg_fetch_result($eredmeny,$i,2));
			echo pg_fetch_result($eredmeny2,0,0);
			echo "</td>";
			echo "<td>";
			$eredmeny2=pg_query($db,"select name from persons where id=".pg_fetch_result($eredmeny,$i,3));
			echo pg_fetch_result($eredmeny2,0,0);
			echo "</td>";
			echo "<td>";
			$eredmeny2=pg_query($db,"select name from offices where id=".pg_fetch_result($eredmeny,$i,4));
			echo pg_fetch_result($eredmeny2,0,0);
			echo "</td>";
			echo "</td>";
			echo "<td>";
			if(pg_fetch_result($eredmeny,$i,8)=='letter'){
				echo "Levél";
			}
			else if(pg_fetch_result($eredmeny,$i,8)=='package'){
				echo "Csomag";
			}
			echo "</td>";
			echo "</td>";
			
			$kerdes2="select locationid from users where username='".$_SESSION['user']."'";
			$eredmeny2=pg_query($db,$kerdes2);
			$sor2=pg_num_rows($eredmeny2);
			$offid=pg_fetch_result($eredmeny2,0,0);
			if($offid==pg_fetch_result($eredmeny,$i,4) || $_SESSION['user']=='admin'){
				echo "<td>";
				
				
				//echo "<span class='error'>".pg_fetch_result($eredmeny,$i,5)."</span>";
				//echo "<span class='error'>".pg_fetch_result($eredmeny,$i,6)."</span>";
				
				if(pg_fetch_result($eredmeny,$i,5)=='t' && pg_fetch_result($eredmeny,$i,6)=='f'){
					echo "<form action='' method='post' style='display: inline;'>";
					echo "<input type='hidden' name='action' value='tovabbit'>";
					echo "<input type='hidden' name='package_id' id='package_".pg_fetch_result($eredmeny,$i,0)."' value='".pg_fetch_result($eredmeny,$i,0)."'>";
					echo "<input type='submit' value='Tovabbitás'></form>";
					echo "</td><td><form action='' method='post' style='display: inline;'>";
					echo "<input type='hidden' name='package_id' id='package_".pg_fetch_result($eredmeny,$i,0)."' value='".pg_fetch_result($eredmeny,$i,0)."'>";
					echo "<input type='hidden' name='action' value='kiszallit'>";
					echo "<input type='submit' value='Kiszállítva'></form>";
				}else if(pg_fetch_result($eredmeny,$i,5)=='f' && pg_fetch_result($eredmeny,$i,6)=='f'){
					echo "<form action='' method='post' stle='display: inline;'>";
					echo "<input type='hidden' name='package_id' id='package_".pg_fetch_result($eredmeny,$i,0)."' value='".pg_fetch_result($eredmeny,$i,0)."'>";
					echo "<input type='hidden' name='action' value='megerkezett'>";
					echo "<input type='submit' value='Megérkezett'></form>";
				}else if(pg_fetch_result($eredmeny,$i,6)=='t'){
					echo "<form action='' method='post' stle='display: inline;'>";
					echo "<input type='hidden' name='package_id' id='package_".pg_fetch_result($eredmeny,$i,0)."' value='".pg_fetch_result($eredmeny,$i,0)."'>";
					echo "<input type='hidden' name='action' value='torles'>";
					echo "<input type='submit' value='Törlés'></form>";
				}
				
				echo "</td>";
			}
			
			
		echo "</tr>";
		echo "<tr id='row_".pg_fetch_result($eredmeny,$i,0)."_b' hidden><td class='sec_td' colspan='12'>";
		echo "Fizetett összeg: ".pg_fetch_result($eredmeny,$i,7)."Ft";
		echo "<br>";
		echo "Feladó címe: ".echoAddress(findAddressByPerson(pg_fetch_result($eredmeny,$i,2)));
		echo "<br>";
		echo "Címzett címe: ".echoAddress(findAddressByPerson(pg_fetch_result($eredmeny,$i,3)));
		echo "<br>";
		echo "Elsőbbségi: ".(pg_fetch_result($eredmeny,$i,9)=='t'?"igen":"nem");
		echo "<br>";
		echo "Ajánlott: ".(pg_fetch_result($eredmeny,$i,10)=='t'?"igen":"nem");
		if(is_numeric(pg_fetch_result($eredmeny,$i,11))){
			echo "<br>";
			echo "Súly: ".pg_fetch_result($eredmeny,$i,11)."kg";
		}
		echo "</td></tr>";
	}
	pg_close($db);
?>
</table>
</main>
<script>
function asd(e){
	let id=e.target.parentElement.id;
	if(id[id.length-1]!='b'){	
		document.getElementById(id).setAttribute('class','main_row_selected');
		id=id.substr(0,id.length-1)+'b';
		document.getElementById(id).hidden=!document.getElementById(id).hidden;
		if(document.getElementById(id).hidden){
			id=id.substr(0,id.length-1)+'a';
			document.getElementById(id).setAttribute('class','main_row');
		}
	}
}

for(let i=0;i<document.getElementsByTagName("tr").length;i++){
	document.getElementsByTagName("tr")[i].addEventListener('click',asd,false);
}
</script>
<script type="text/javascript" src="js.js"></script>
</body>
</html>