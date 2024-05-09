<?php
session_start();
include "header.php"
?>

<?php
	if(isset($_POST['email']) && isset($_POST['jelszo'])){
	$hibak=["Az email kitöltése kötelező!","A jelszó kitöltése kötelező!","Hibás email formátum!","Hibás email vagy jelszó!"];
	$hiba=[];
	if($_POST['email']==""){$hiba[]=$hibak[0];}
	elseif((strpos($_POST['email'], "@")==false) || (strpos($_POST['email'], ".")==false)){$hiba[]=$hibak[2];}
	elseif(strpos($_POST['email'], "@")>strrpos($_POST['email'], ".")){$hiba[]=$hibak[2];}
	if($_POST['jelszo']==""){$hiba[]=$hibak[1];}
	if(count($hiba)==0){
		$users=json_decode(file_get_contents("users.txt"));
		$i=0;
		while($i<count($users) && ($users[$i]->email != $_POST["email"] && $users[$i]->jelszo != $_POST["jelszo"])){
			$i++;
		}
		if($i==count($users)){$hiba[]=$hibak[3];}
		else{$_SESSION['user']=$users[$i];}
		unset($i);
		unset($users);
	}
	}
?>
<main>
<div class="loginbox">
	<h2>Belépés</h2>
	<?php
		if(isset($_POST['email']) && isset($_POST['jelszo'])){
			if(count($hiba)>0){
				echo "<ul>";
			foreach($hiba as $h){
				echo "<li>" . $h . "</li>";
			}
			echo "</ul>";
			}else{
				header("Location: index.php");
				exit();
			}
		}
	?>
	<form action="" method="post" novalidate>
		<label for="email">Email:</label>
		<input type="text" name="email">
		<br>
		<label for="jelszo">Jelszó:</label>
		<input type="password" name="jelszo">
		<br>
	<input type="submit" value="Belépés">
	<input type="button" value="Mégsem" onclick="cancel()">
	</form>
</div>
</main>
<script>
function cancel(){
	window.location.href="index.php";
}
</script>
</body>
</html>