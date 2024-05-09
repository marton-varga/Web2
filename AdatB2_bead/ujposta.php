<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
else if(isset($_SESSION['user']) && $_SESSION['user']!='admin'){
	header("Location: ./index.php");
}
include "adatok.php";
?>

<?php
$hibak=[];
/*
101 - name required
111 - zip required
112 - invalid zip
131 - county required
141 - settlement required
151 - street required
161 - number required
201 - rank required
301 - office exists
*/
if(isset($_POST['name']) || isset($_POST['zipcode']) || isset($_POST['county']) || isset($_POST['settlement']) || isset($_POST['street']) || isset($_POST['number']) || isset($_POST['rank'])){
	//name
	if(!isset($_POST['name']) || strlen(trim($_POST['name']))==0){
		$hibak[]=101;
	}
	//zipcode
	if(!isset($_POST["zipcode"]) || strlen(trim($_POST["zipcode"]))==0){
		$hibak[]=111;
	}else if(strlen($_POST["zipcode"])!=4 || !is_numeric($_POST["zipcode"])){
		$hibak[]=112;
	}
	//county
	if(!isset($_POST["county"]) || strlen(trim($_POST["county"]))==0){
		$hibak[]=131;
	}
	//settlement
	if(!isset($_POST["settlement"]) || strlen(trim($_POST["settlement"]))==0){
		$hibak[]=141;
	}
	//street
	if(!isset($_POST["street"]) || strlen(trim($_POST["street"]))==0){
		$hibak[]=151;
	}
	//number
	if(!isset($_POST["number"]) || strlen(trim($_POST["number"]))==0){
		$hibak[]=161;
	}
	//rank
	if(!isset($_POST['rank']) || strlen(trim($_POST['rank']))==0){
		$hibak[]=201;
	}
	else{
		$_POST['rank']=strtolower($_POST['rank']);
	}
}

function getOrCreateAddress(){
	$adatbazis='vtx73j';
	$user='vtx73j';
	$jelszo='vtx73j';
	$port=5432;
	$host='localhost';
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select * from addresses where country='"."Magyarország"."' and county='".$_POST["county"]."' and settlement='".$_POST["settlement"]."' and street='".$_POST["street"]."' and number='".$_POST["number"]."' and zipcode='".$_POST["zipcode"]."'";
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	if($sor==1){
		$id=pg_fetch_result($eredmeny,0,0);
	}else{
		$id=pg_fetch_result(pg_query($db,"select max(id) from addresses"),0,0)+1;
		$kerdes="insert into addresses values(".$id.",'"."Magyarország"."','".$_POST["county"]."','".$_POST["settlement"]."','".$_POST["street"]."','".$_POST["number"]."','".$_POST["zipcode"]."')";
		pg_query($db,$kerdes);
	}
	
	////
	pg_close($db);
	return $id;
}

if(count($hibak)==0 && (isset($_POST['name']) && isset($_POST['zipcode']) && isset($_POST['county']) && isset($_POST['settlement']) && isset($_POST['street']) && isset($_POST['number']) && isset($_POST['rank']))){
	
	//address
	$aid=getOrCreateAddress();
	
	//check
	$db=pg_connect("host=$host port=$port dbname=$adatbazis user=$user password=$jelszo");
	$kerdes="select * from offices where address=".$aid." and name='".$_POST['name']."'";
	$eredmeny=pg_query($db,$kerdes);
	$sor=pg_num_rows($eredmeny);
	if($sor==1){
		$hibak[]=301;
	}
	
	//insertinto
	if(count($hibak)==0){
		$kerdes="insert into offices values(default,'".$_POST['name']."',".$aid.",'".$_POST['rank']."')";
		$eredmeny=pg_query($db,$kerdes);
		$sor=pg_num_rows($eredmeny);
		pg_close($db);
		header("Location: ./sikeres.php");
	}
	pg_close($db);
}
?>

<?php include "header.php"; ?>
<main><h1>Új posta felvétele</h1>

<div class="box">
	<?php
	if(in_array(301,$hibak)){
		echo "<br><span class='error'>A megadott posta már létezik</span>";
	}
	?>
	<form action="" method="post">
		<label for="name"><b>Név:</b></label>
		<br>
		<input type="text" name="name" <?php if(isset($_POST['name'])){echo "value='".$_POST['name']."'";}?>>
			<?php
			if(in_array(101,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span>";
			}
			?>
		<br>
		<br>
		
		<span><b>Cím:</b></span>
		
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
			<input type="text" size="4" name="zipcode" <?php if(isset($_POST['zipcode'])){echo "value='".$_POST['zipcode']."'";}?>>
			<?php
			if(in_array(111,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}else if(in_array(112,$hibak)){
				echo "<br><span class='error'>Hibás formátum! A magyarországi irányítószám 4 számjegyből áll!</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" value="Magyarország" disabled>
			</td><td>
			<input type="text" name="county" <?php if(isset($_POST['county'])){echo "value='".$_POST['county']."'";}?>>
			<?php
			if(in_array(131,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="settlement" <?php if(isset($_POST['settlement'])){echo "value='".$_POST['settlement']."'";}?>>
			<?php
			if(in_array(141,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="street" <?php if(isset($_POST['street'])){echo "value='".$_POST['street']."'";}?>>
			<?php
			if(in_array(151,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td><td>
			<input type="text" name="number" <?php if(isset($_POST['number'])){echo "value='".$_POST['number']."'";}?>>
			<?php
			if(in_array(161,$hibak)){
				echo "<br><span class='error'>A mező kitöltése kötelező</span><br><br>";
			}
			?>
			</td>
		</tr>
		</table>
		<br>
		<label for="rank"><b>Rang:</b></label>
		<br>
		<input type="text" name="rank" <?php if(isset($_POST['rank'])){echo "value='".$_POST['rank']."'";}?>>
		<?php
		if(in_array(201,$hibak)){
			echo "<br><span class='error'>A mező kitöltése kötelező</span>";
		}
		?>
		<br>
		<br>	
	<input type="submit" value="Posta felvétele">
</form>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>