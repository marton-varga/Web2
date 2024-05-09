<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
include "adatok.php";

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
$hibak = [];
/*
1/2: sender, receiver
101 - sender required
102 - sender name requires space char_from_digit
111 - zipcode required
112 - if(HUN) zipcode is_numeric and count==4
121 - country required
131 - county required
141 - settlement required
151 - street required
161 - number required

301 - letter/package required
311 - if(package): suly required
312 - if(package): invalid suly
401 - invalid ajánlott value
402 - invalid elsobbsegi value
*/
$hibak2 = [];
/*
501 - if(notUniquePrice && calculatedPrice not valid): invalid calculated price
502 - if(uniquePrice) invalid
601 - invalid normalPrice
*/

function checkPersonAndAddress($a){
	global $hibak;
	$hiba=100;
	if($a!=1 && $a!=2){die("Hibás adat: ".$a."!");}
	$hiba=$hiba*$a;
	if(isset($_POST["name_$a"]) || isset($_POST["zipcode_$a"]) || isset($_POST["country_$a"]) || isset($_POST["county_$a"]) || isset($_POST["settlement_$a"]) || isset($_POST["street_$a"]) || isset($_POST["number_$a"])){
	//personAndAddress
		//name
		if(!isset($_POST["name_$a"]) || strlen(trim($_POST["name_$a"]))==0){
			$hibak[]=$hiba+1;
		}else{
			$i=0;
			while($i<strlen($_POST["name_$a"])-1 && $_POST["name_$a"][$i]!=' '){
				$i++;
			}
			if($i>=strlen($_POST["name_$a"])-1){
				$hibak[]=$hiba+2;
			}
			unset($i);
		}
		//zipcode
		if(!isset($_POST["zipcode_$a"]) || strlen(trim($_POST["zipcode_$a"]))==0){
			$hibak[]=$hiba+11;
		}else if(!isset($_POST["country_$a"]) || (strtolower($_POST["country_$a"])=="magyarország" && (strlen($_POST["zipcode_$a"])!=4 || !is_numeric($_POST["zipcode_$a"])))){
			$hibak[]=$hiba+12;
		}
		//country
		if(!isset($_POST["country_$a"]) || strlen(trim($_POST["country_$a"]))==0){
			$hibak[]=$hiba+21;
		}else{
			$orszag=strtoupper($_POST["country_$a"][0]).strtolower(substr($_POST["country_$a"],1));
			$_POST["country_$a"]=$orszag;
		}
		//county
		if(!isset($_POST["county_$a"]) || strlen(trim($_POST["county_$a"]))==0){
			$hibak[]=$hiba+31;
		}
		//settlement
		if(!isset($_POST["settlement_$a"]) || strlen(trim($_POST["settlement_$a"]))==0){
			$hibak[]=$hiba+41;
		}
		//street
		if(!isset($_POST["street_$a"]) || strlen(trim($_POST["street_$a"]))==0){
			$hibak[]=$hiba+51;
		}
		//number
		if(!isset($_POST["number_$a"]) || strlen(trim($_POST["number_$a"]))==0){
			$hibak[]=$hiba+61;
		}
	}
}

$igaz=(isset($_POST['name_1']) || isset($_POST['zipcode_1']) || isset($_POST['country_1']) || isset($_POST['county_1']) || isset($_POST['settlement_1']) || isset($_POST['street_1']) || isset($_POST['number_1']) || isset($_POST['name_2']) || isset($_POST['zipcode_2']) || isset($_POST['country_2']) || isset($_POST['county_2']) || isset($_POST['settlement_2']) || isset($_POST['street_2']) || isset($_POST['number_2']) || isset($_POST['type']));
if($igaz){
	checkPersonAndAddress(1);
	checkPersonAndAddress(2);
	//type
	if(!isset($_POST['type'])){
		$hibak[]=301;
	}else if(($_POST['type']!='letter' && $_POST['type']!='package')){
		die("Hibás érték!");
	}
	//weight
	
	if(isset($_POST['type']) && $_POST['type']=='package'){
		if(!isset($_POST['weight'])){
			$hibak[]=311;
		}else if(!is_numeric($_POST['weight']) || $_POST['weight']<0){
			$hibak[]=312;
		}
	}	
	//ajanlott
	if(isset($_POST['ajanlott']) && $_POST['ajanlott']!='on' ){
		die("Hibás érték!");
	}
	//elsobbsegi
	if(isset($_POST['elsobbsegi']) && $_POST['elsobbsegi']!='on' ){
		die("Hibás érték!");
	}
}

$igaz=((count($hibak)==0 && !isset($_POST['cancel']) && (isset($_POST['uniquePrice']) || isset($_POST['normalPrice']))) && (isset($_POST['name_1']) && isset($_POST['zipcode_1']) && isset($_POST['country_1']) && isset($_POST['county_1']) && isset($_POST['settlement_1']) && isset($_POST['street_1']) && isset($_POST['number_1']) && isset($_POST['name_2']) && isset($_POST['zipcode_2']) && isset($_POST['country_2']) && isset($_POST['county_2']) && isset($_POST['settlement_2']) && isset($_POST['street_2']) && isset($_POST['number_2']) && isset($_POST['type'])));

function getOrCreatePersonWithAddress($a){
	$adatbazis='vtx73j';
	$user='vtx73j';
	$jelszo='vtx73j';
	$port=5432;
	$host='localhost';
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select * from addresses where country='".$_POST["country_$a"]."' and county='".$_POST["county_$a"]."' and settlement='".$_POST["settlement_$a"]."' and street='".$_POST["street_$a"]."' and number='".$_POST["number_$a"]."' and zipcode='".$_POST["zipcode_$a"]."'";
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	if($sor==1){
		$id=pg_fetch_result($eredmeny,0,0);
	}else{
		$id=pg_fetch_result(pg_query($db,"select max(id) from addresses"),0,0)+1;
		$kerdes="insert into addresses values(".$id.",'".$_POST["country_$a"]."','".$_POST["county_$a"]."','".$_POST["settlement_$a"]."','".$_POST["street_$a"]."','".$_POST["number_$a"]."','".$_POST["zipcode_$a"]."')";
		pg_query($db,$kerdes);
	}
	////
	$kerdes="select * from persons where name='".$_POST["name_$a"]."' and addressid=".$id;
	$eredmeny=pg_query($db,$kerdes);
	if(pg_num_rows($eredmeny)==1){
		$pid=pg_fetch_result($eredmeny,0,0);
	}else{
		$kerdes="select max(id) from persons";
		$eredmeny=pg_query($db,$kerdes);
		$pid=pg_fetch_result($eredmeny,0,0)+1;
		$kerdes="insert into persons values(".$pid.",'".$_POST["name_$a"]."',".$id.")";
		pg_query($db,$kerdes);
	}
	pg_close($db);
	return $pid;
}

if($igaz){
	//price
	if(isset($_POST['unique'])){
		if(!isset($_POST['uniquePrice']) || !is_numeric($_POST['uniquePrice'])){
			$hibak2[]=501;
		}else if($_POST['uniquePrice']<0){
			$hibak2[]=502;
		}
	}else if(!isset($_POST['normalPrice']) || !is_numeric($_POST['normalPrice'])){
		$hibak2[]=601;
		//die("Hibás érték!");
	}else if($_POST['normalPrice']<0){
		$hibak2[]=601;
		//die("Hibás érték!");
	}
	//SUCCESS
	if(count($hibak2)==0){
		$pid1=getOrCreatePersonWithAddress(1);
		$pid2=getOrCreatePersonWithAddress(2);

		$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
		$kerdes="select max(id) from packages";
		$eredmeny=pg_query($db,$kerdes);
		$packId=pg_fetch_result($eredmeny,0,0)+1;

		$kerdes="select locationid from users where username='".$_SESSION['user']."'";
		$eredmeny=pg_query($db,$kerdes);
		$office=pg_fetch_result($eredmeny,0,0);
		$pricePayed=0;
		if(isset($_POST['uniquePrice'])){
			$pricePayed=$_POST['uniquePrice'];
		}else{
			$pricePayed=$_POST['normalPrice'];
		}
		$els=$POST['elsobbsegi']=='on'?'t':'f';
		$ajl=$POST['ajanlott']=='on'?'t':'f';
		
		$suly="null";
		if($_POST['type']=='package'){
			$suly=$_POST['weight'];
		}
		
		$kerdes="insert into packages values(".$packId.",'".date("Y-m-d")."',".$pid1.",".$pid2.",".$office.",'t','f',".$pricePayed.",'".$_POST['type']."','".$els."','".$ajl."',".$suly.")";
		$asd=$kerdes;
		pg_query($db,$kerdes);
		pg_close($db);
		header("Location: ./sikeres.php");
	}
}
?>
<?php
$igaz=(count($hibak)==0 && isset($_POST['name_1']) && isset($_POST['zipcode_1']) && isset($_POST['country_1']) && isset($_POST['county_1']) && isset($_POST['settlement_1']) && isset($_POST['street_1']) && isset($_POST['number_1']) && isset($_POST['name_2']) && isset($_POST['zipcode_2']) && isset($_POST['country_2']) && isset($_POST['county_2']) && isset($_POST['settlement_2']) && isset($_POST['street_2']) && isset($_POST['number_2']) && isset($_POST['type']) && !isset($_POST['cancel']));
?>




<?php include "header.php"; ?>
<div class="messageShader" id="confirmDelete" <?php if(!$igaz) echo "hidden"?>>
<?php
include_once "adatok.php";
if($igaz){		
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$eredmeny=pg_query($db,"select * from prices");
	$sor=pg_num_rows($eredmeny);
	
	echo "<div class='message'>";
	echo "<p><b>Feladó:</b><br>".$_POST["name_1"].", ".strtoupper($_POST['country_1']).", ".$_POST['zipcode_1']." ".$_POST['county_1'].", ".$_POST['settlement_1'].", ".$_POST['street_1']." ".$_POST['number_1']."</p>";
	echo "<p><b>Címzett:</b><br>".$_POST["name_2"].", ".strtoupper($_POST['country_2']).", ".$_POST['zipcode_2']." ".$_POST['county_2'].", ".$_POST['settlement_2'].", ".$_POST['street_2']." ".$_POST['number_2']."</p>";
	$szamit=0;
	echo "<table>";
	echo "<tr>";
	if($_POST['type']=='letter'){
		echo "<th>Küldemény típusa</th><td>Levél</td><td>".pg_fetch_result(pg_query($db,"select price from prices where name='Levél'"),0,0)."Ft</td></tr>";
		$szamit=pg_fetch_result(pg_query($db,"select price from prices where name='Levél'"),0,0);
	}else if($_POST['type']=='package'){
		echo "<th>Küldemény típusa</th><td>Csomag</td><td>".pg_fetch_result(pg_query($db,"select price from prices where name='Csomag'"),0,0)."Ft</td>";
		$szamit=pg_fetch_result(pg_query($db,"select price from prices where name='Csomag'"),0,0);
		echo "</tr><tr>";
		echo "<th>Súly szorzó</th><td>".pg_fetch_result(pg_query($db,"select price from prices where name='Súly szorzó'"),0,0)."</td>";
		echo "</tr><tr>";
		$szamit+=pg_fetch_result(pg_query($db,"select price from prices where name='Súly szorzó'"),0,0)*$_POST['weight'];
		echo "<th>Súlya</th><td>".$_POST['weight']."kg</td><td>".pg_fetch_result(pg_query($db,"select price from prices where name='Súly szorzó'"),0,0)*$_POST['weight']."Ft</td></tr>";
	}
	echo "<tr><th>Ajánlott</th>";
	if(isset($_POST['ajanlott'])){
		echo "<td>Igen</td>";
		echo "<td>".pg_fetch_result(pg_query($db,"select price from prices where name='Ajánlott'"),0,0)."Ft</td>";
		$szamit+=pg_fetch_result(pg_query($db,"select price from prices where name='Ajánlott'"),0,0);
	}else{
		echo "<td>Nem</td>";		
	}
	echo "</tr>";
	echo "<tr><th>Elsőbbségi</th>";
	if(isset($_POST['elsobbsegi'])){
		echo "<td>Igen</td>";
		echo "<td>".pg_fetch_result(pg_query($db,"select price from prices where name='Elsőbbségi'"),0,0)."Ft</td>";
		$szamit+=pg_fetch_result(pg_query($db,"select price from prices where name='Elsőbbségi'"),0,0);
	}else{
		echo "<td>Nem</td>";		
	}
	echo "</tr>";
	echo "<tr><th>Küldöldi</th>";
	if($_POST['country_2']!='Magyarország'){
		echo "<td>Igen</td>";
		echo "<td>".pg_fetch_result(pg_query($db,"select price from prices where name='Külföldi'"),0,0)."Ft</td>";
		$szamit+=pg_fetch_result(pg_query($db,"select price from prices where name='Külföldi'"),0,0);
	}else{
		echo "<td>Nem</td>";		
	}
	echo "</tr>";
	echo "</table>";
	echo "<h2 id='ossz'>Összesen: ".$szamit."Ft</h2>";
	///
	if(in_array($hibak2,601)){
		echo "<span class='error'>Hibás érték!</span>";
	}
	///
	echo "<form style='display: inline;' action='' method='post'>";
	
	echo "<input id='unique' type='checkbox' name='unique'";
	if(isset($_POST['unique']) && $_POST['unique']=='on'){
		echo " checked";
	}
	echo "<label for='unique'>Egyéni ár </label>";
	echo "<input type='number' id='uniquePrice' name='uniquePrice' min='0'";
	if(isset($_POST['uniquePrice']) && is_numeric($_POST['uniquePrice']) && $_POST['uniquePrice']>=0){
		echo " value='".$_POST['uniquePrice']."'";
	}
	if(!isset($_POST['unique']) || ($_POST['unique']!='on')){
		echo " disabled";
	}
	echo">";

	////
	if(in_array(501,$hibak2)){
		echo "<br><span class='error'>Egyedi ár esetén a mező kitöltése kötelező!</span><br>";
	}else if(in_array(502,$hibak2)){
		echo "<br><span class='error'>Hibás érték!</span><br>";
	}
	
	foreach($_POST as $k=>$v){
		if($k!='unique' && $k!='uniquePrice' && $k!='normalPrice'){
			echo "<input type='hidden' name='".$k."' value='".$v."'>";
		}
	}
	echo "<input type='hidden' name='normalPrice' value='".$szamit."'>";
	echo "<br><br>";
	echo "<input type='submit' value='Küldemény felvétele'>";
	echo "</form>";
	echo "<form style='display: inline;' action='' method='post'>";
	echo "<input type='hidden' name='cancel' value='on'>";
	foreach($_POST as $k=>$v){
		echo "<input type='hidden' name='".$k."' value='".$v."'>";
	}
	echo "<input type='submit' value='Mégsem'></form>";
	echo "</div>";
	pg_close($db);
}
?>
</div>





<main>
<?php

?>
<div class="box">
	<form action="" method="post">
		<div class="left">
		<label for="name_1"><b>Feladó Neve:</b></label><br>
		<input type="text" name="name_1" <?php if(isset($_POST['name_1'])){echo "value='".$_POST['name_1']."'";}?>>
		<br>
		<?php
			if(in_array(101,$hibak)){
				echo "<span class='error'>A mező kitöltése kötelező</span><br><br>";
			}else if(in_array(102,$hibak)){
				echo "<span class='error'>Hibás formátum! A névnek tartalmaznia kell szóközt!</span><br><br>";
			}
		?>
		<span><b>Címe:</b></span>
		</div>
		<table>
		<tr>
			<th><label for="zipcode_1">Irányítószám</label></th>
			<th><label for="country_1">Ország</label></th>
			<th><label for="county_1">Megye</label></th>
			<th><label for="settlement_1">Település</label></th>
			<th><label for="street_1">Utca</label></th>
			<th><label for="number_1">Házszám</label></th>
		</tr>
		<tr>
			<td>
			<input type="text" size="4" name="zipcode_1" <?php if(isset($_POST['zipcode_1'])){echo "value='".$_POST['zipcode_1']."'";}?>>
			<?php
			if(in_array(111,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}else if(in_array(112,$hibak)){
				echo "<br><span class='error'>Hibás formátum! A magyarországi irányítószám 4 számjegyből áll!</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="country_1" <?php if(isset($_POST['country_1'])){echo "value='".$_POST['country_1']."'";}?>>
			<?php
			if(in_array(121,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="county_1" <?php if(isset($_POST['county_1'])){echo "value='".$_POST['county_1']."'";}?>>
			<?php
			if(in_array(131,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="settlement_1" <?php if(isset($_POST['settlement_1'])){echo "value='".$_POST['settlement_1']."'";}?>>
			<?php
			if(in_array(141,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="street_1" <?php if(isset($_POST['street_1'])){echo "value='".$_POST['street_1']."'";}?>>
			<?php
			if(in_array(151,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="number_1" <?php if(isset($_POST['number_1'])){echo "value='".$_POST['number_1']."'";}?>>
			<?php
			if(in_array(161,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td>
		</tr>
		</table>
		<br>
		<div class="left">
		<label for="name_2"><b>Címzett Neve:</b></label><br>
		<input type="text" name="name_2" <?php if(isset($_POST['name_2'])){echo "value='".$_POST['name_2']."'";}?>>
		<br>
		<?php
			if(in_array(201,$hibak)){
				echo "<span class='error'>A mező kitöltése kötelező</span><br><br>";
			}else if(in_array(202,$hibak)){
				echo "<span class='error'>Hibás formátum! A névnek tartalmaznia kell szóközt!</span><br><br>";
			}
		?>
		<span><b>Címe:</b></span>
		</div>
		<table>
		<tr>
			<th><label for="zipcode_2">Irányítószám</label></th>
			<th><label for="country_2">Ország</label></th>
			<th><label for="county_2">Megye</label></th>
			<th><label for="settlement_2">Település</label></th>
			<th><label for="street_2">Utca</label></th>
			<th><label for="number_2">Házszám</label></th>
		</tr>
		<tr>
			<td>
			<input type="text" size="4" name="zipcode_2" <?php if(isset($_POST['zipcode_2'])){echo "value='".$_POST['zipcode_2']."'";}?>>
			<?php
			if(in_array(211,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}else if(in_array(212,$hibak)){
				echo "<br><span class='error'>Hibás formátum! A magyarországi irányítószám 4 számjegyből áll!</span><br><br>";
			}
			?>

			</td><td>
			<input type="text" name="country_2" <?php if(isset($_POST['country_2'])){echo "value='".$_POST['country_2']."'";}?>>
			<?php
			if(in_array(221,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="county_2" <?php if(isset($_POST['county_2'])){echo "value='".$_POST['county_2']."'";}?>>
			<?php
			if(in_array(231,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="settlement_2" <?php if(isset($_POST['settlement_2'])){echo "value='".$_POST['settlement_2']."'";}?>>
			<?php
			if(in_array(241,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="street_2" <?php if(isset($_POST['street_2'])){echo "value='".$_POST['street_2']."'";}?>>
			<?php
			if(in_array(251,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="number_2" <?php if(isset($_POST['number_2'])){echo "value='".$_POST['number_2']."'";}?>>
			<?php
			if(in_array(261,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td>
		</tr>
		</table>
		<div class="left">
			<input type="radio" id="level" name="type" value="letter" <?php echo (!isset($_POST['type']) || $_POST['type']=='letter')? "checked":""; ?>>
			<label for="level">Levél</label>
			<input type="radio" id="csomag" name="type" value="package" <?php echo (isset($_POST['type']) && $_POST['type']=='package')? "checked":""; ?>>
			<label for="csomag">Csomag</label>
			<div id="suly" <?php echo (!isset($_POST['type']) || $_POST['type']!='package')? "hidden":""; ?>>
				<label for="weight">Súly: </label>
				<input type="number" min=0 name="weight" <?php echo (isset($_POST['weight']) && is_numeric($_POST['weight']))? "value='".$_POST['weight']."'":""; ?>>
				<?php
					if(in_array(311 ,$hibak)){
						echo "<br><span class='error'>A mező kitöltése kötelező!</span><br>";
					}else if(in_array(312 ,$hibak)){
						echo "<br><span class='error'>Hibás érték!</span><br>";
					}
				?>
			</div>
			<br>
			<input type="checkbox" id="ajanlott" name="ajanlott" <?php echo (isset($_POST['ajanlott']) && $_POST['ajanlott']==true)? "checked":""; ?>>
			<label for="ajanlott">Ajánlott</label>
			<br>
			<input type="checkbox" id="elsobbsegi" name="elsobbsegi" <?php echo (isset($_POST['elsobbsegi']) && $_POST['elsobbsegi']==true)? "checked":""; ?>>
			<label for="elsobbsegi">Elsőbbségi</label>
		</div>
		<br>
	<input type="submit" value="Küldemény felvétele">
</form>
</div>
</main>
<script>
function csomagChecked(){
	if(document.getElementById("csomag").checked==true){
		document.getElementById("suly").hidden=false;
	}else{
		document.getElementById("suly").hidden=true;		
	}
}
function uniqueChecked(){
	if(document.getElementById('unique')){
		document.getElementById('uniquePrice').disabled=!document.getElementById('uniquePrice').disabled;
		document.getElementById('ossz').style.color=document.getElementById('uniquePrice').disabled? "black" : "gray";
	}
}
if(document.getElementById('ossz')){
	document.getElementById('ossz').style.color=document.getElementById('uniquePrice').disabled? "black" : "gray";
}
document.getElementById("level").addEventListener('click',csomagChecked,false);
document.getElementById("csomag").addEventListener('click',csomagChecked,false);
document.getElementById("unique").addEventListener('click',uniqueChecked,false);
</script>
<script type="text/javascript" src="js.js"></script>
</body>
</html>