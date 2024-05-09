<?php
session_start();
include "header.php";

class Vetites{
	var $id;
	var $cim;
	var $datum;
	var $nyelv;
	var $terem;
	var $helyek;
	var $ar;
	var $foglalasok;
	var $aktiv;
	function __construct($c_,$n_,$d_,$t_,$h_,$a_,$s_){
		$this->cim=$c_;
		$this->datum=$d_;
		$this->nyelv=$n_;
		$this->terem=$t_;
		$this->helyek=$h_;
		$this->ar=$a_;
		$this->foglalasok=[];
		$this->aktiv=$s_;
		$vetitesek=json_decode(file_get_contents("vetitesek.txt"));
		$max=0;
		foreach($vetitesek as $v){
			if($v->id > $max){$max=$v->id;}
		}
		$this->id=$max+1;
	}
}
class Film{
	var $id;
	var $cim;
	var $leiras;
	var $link;
	function __construct($c_,$s_,$l_){
		$this->cim=$c_;
		$this->leiras=$s_;
		$this->link=$l_;
		$filmek=json_decode(file_get_contents("filmek.txt"));
		$max=0;
		foreach($filmek as $f){
			if($f->id > $max){$max=$f->id;}
		}
		$this->id=$max+1;
	}
}
?>

<?php
	
	$hibak=["A mező kitöltése kötelező", "Hibás formátum", "A film már szerepel a listán"];
	$hiba=["cim"=>[],"filmcim"=>[],"nyelv"=>[],"datum"=>[],"terem"=>[],"helyek"=>[],"ar"=>[]];
		if(isset($_POST['cim']) || isset($_POST['nyelv']) || isset($_POST['datum']) || isset($_POST['terem']) || isset($_POST['helyek']) || isset($_POST['ar'])){
				
		//cim
		if($_POST['cim']=="Új film" && ((!isset($_POST['filmcim'])) || $_POST['filmcim']=="")){array_push($hiba['filmcim'],$hibak[0]);}
		elseif($_POST['cim']=='Új film'){
			$i=0;
			$filmek=json_decode(file_get_contents("filmek.txt"));
			while($i<count($filmek) && $_POST['filmcim']!=$filmek[$i]->cim){
				$i++;
			}
			if($i<count($filmek)){
				array_push($hiba['filmcim'],$hibak[2]);
			}
		}
		//nyelv
		if($_POST['nyelv']==""){array_push($hiba['nyelv'],$hibak[0]);}
		//datum
		if($_POST['datum']==""){array_push($hiba['datum'],$hibak[0]);}
		//helyek
		if($_POST['helyek']==""){array_push($hiba['helyek'],$hibak[0]);}
		elseif(!is_numeric($_POST['helyek']) || $_POST['helyek']<1){array_push($hiba['helyek'],$hibak[1]);}
		//ar
		if($_POST['ar']==""){array_push($hiba['ar'],$hibak[0]);}
		elseif(!is_numeric($_POST['ar']) || $_POST['ar']<1){array_push($hiba['ar'],$hibak[1]);}
	}
	
	function hibakiir($str){
		global $hiba;		
		if(count($hiba[$str])>0){
			echo "<ul>";
			foreach($hiba[$str] as $h){
				echo "<li>" . $h . "</li>";
			}
			echo "</ul>";
		}
	}
	
?>
<main>
<div class="loginbox" id="ujfilmbox">
	<h2>Új vetítés felvétele</h2>
	<?php
			
		
		$van=false;
		if(isset($_POST['cim']) || isset($_POST['nyelv']) || isset($_POST['datum']) || isset($_POST['terem']) || isset($_POST['helyek']) || isset($_POST['ar'])){
			$i=0;
			foreach($hiba as $h){
				if(count($h)>0){$van=true;break;}
			}
			if(!$van){
				$c=$_POST['cim'];
				if($_POST['cim']=="Új film"){
					$c=$_POST['filmcim'];
					$s="";
					$l="";
					if(isset($_POST['filmleiras']) && $_POST['filmleiras']!=""){$s=$_POST['filmleiras'];}
					if(isset($_POST['filmlink']) && $_POST['filmlink']!=""){$l=$_POST['filmlink'];}
					$film = new Film($c, $s, $l);
					$filmek=json_decode(file_get_contents("filmek.txt"));
					$filmek[]=$film;
					file_put_contents("filmek.txt", json_encode($filmek));
				}
				$_POST['datum']=str_replace("T", " ", $_POST['datum']);
				$a=isset($_POST['aktiv']);
				$vetites=new Vetites($c, $_POST['nyelv'], $_POST['datum'], $_POST['terem'], $_POST['helyek'], $_POST['ar'], $a);
				$vetitesek=json_decode(file_get_contents("vetitesek.txt"));
				$vetitesek[]=$vetites;
				
				file_put_contents("vetitesek.txt", json_encode($vetitesek));
				header("Location: index.php");
				exit();
			}
			
		}
	?>
	<form action="" method="post" novalidate>
		<label for="cim">Cím:</label>
		<select onchange="ujfilm()" id="filmlista" name="cim" value="<?php if(isset($_POST['cim'])){echo $_POST['cim'];} ?>">
			<?php
				foreach($filmek as $f){
					echo "<option value='" . $f->cim . "'";
					if(isset($_POST['cim']) && $_POST['cim']==$f->cim){echo " selected";}
					echo ">";
					echo $f->cim;
					echo "</option>";
				}
				echo "<option value='Új film'";
				if(isset($_POST['cim']) && $_POST['cim']=="Új film"){echo " selected";}
				echo ">";
				echo "Új film";
				echo "</option>";
			?>
		</select>
			<?php hibakiir('cim'); ?>
		<div id="ujfilm" <?php
			if(!(isset($_POST['cim']) && $_POST['cim']=="Új film")){echo "hidden";}
			
			?>
		
		>
		<label for="filmcim">Filmcím:</label>
		<input type="text" name="filmcim" value="<?php if(isset($_POST['filmcim'])){echo $_POST['filmcim'];} ?>">
			<?php hibakiir('filmcim'); ?>
		<br>
		<label for="filmleiras">Leírás:</label><br>
		<textarea name="filmleiras"><?php if(isset($_POST['filmleiras'])){echo $_POST['filmleiras'];} ?></textarea>
		<br>
		<label for="filmcim">Link:</label>
		<input type="text" name="filmlink" value="<?php if(isset($_POST['filmlink'])){echo $_POST['filmlink'];} ?>">
		</div>
		<br>
			
		<label for="nyelv">Nyelv:</label>
		<input type="text" name="nyelv" value="<?php if(isset($_POST['nyelv'])){echo $_POST['nyelv'];} ?>">
		<br>
			<?php hibakiir('nyelv') ?>
		<label for="datum">Időpont:</label>
		<input type="datetime-local" name="datum" value="<?php if(isset($_POST['datum'])){echo $_POST['datum'];} ?>">
		<br>
			<?php hibakiir('datum'); ?>
		
		<label for="terem">Terem:</label>
		<select name="terem" value="<?php if(isset($_POST['terem'])){echo $_POST['terem'];} ?>">
			<?php
				$termek=json_decode(file_get_contents("termek.txt"));
				for($i=0; $i<count($termek);$i++){
					echo "<option value='" . $termek[$i] . "'>";
					echo $termek[$i];
					echo "</option>";
				}
				unset($termek);
			?>
		</select>
		<br>
			<?php hibakiir('terem'); ?>
		<label for="helyek">Jegyek száma:</label>
		<input type="number" name="helyek" min="1" value="<?php if(isset($_POST['helyek'])){echo $_POST['helyek'];} ?>">
		<br>
			<?php hibakiir('helyek'); ?>
		<label for="ar">Ár:</label>
		<input type="number" name="ar" min="1" value="<?php if(isset($_POST['ar'])){echo $_POST['ar'];} ?>">
		<br>
			<?php hibakiir('ar'); ?>
		<label for="aktiv">Aktív:</label>
		<input type="checkbox" name="aktiv" value="aktiv" checked>
		<br>
		
	<input type="submit" value="Film felvétele">
	<input type="button" value="Mégsem" onclick="cancel()">
	</form>
</div>
</main>
<script>
ujfilm();
function ujfilm(){
	if(document.getElementById("filmlista").value=="Új film"){
		document.getElementById("ujfilm").hidden=false;
	}else{
		document.getElementById("ujfilm").hidden=true;
	}
}
function cancel(){
	window.location.href="index.php";
}
</script>
</body>
</html>