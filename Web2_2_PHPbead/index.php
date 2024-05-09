<?php
session_start();
include "header.php";
if(isset($_SESSION['vetites'])){unset($_SESSION['vetites']);}


?>

<main id="indexmain">
<h2>Vetítések</h2>
<h3></h3>
<table id="tabla"></table>
<p style="text-align: center;">
<button id="prev" onclick="elozohet()">Előző heti vetítések</button>
<button id="next" onclick="kovetkezohet()">Következő heti vetítések</button>
</p>
</main>

<script>
var mindmutat=true;
var date = new Date();
var dateK = new Date();
var dateV = new Date();

bontas("HetfotolHetfoig");
function bontas(x){
	
	switch(x){
		case "HetfotolHetfoig":
			dateK.setDate(dateK.getDate() - (dateK.getDay() + 6) % 7);
			dateV.setDate(dateV.getDate() + (1 + 7 - dateV.getDay()) % 7);
			if(dateK.getDate()==dateV.getDate()){
				dateV = new Date(dateK.getTime()+(7*24*60*60*1000));
			}
			break;
		case "+-3Napot":
			dateK = new Date(date.getTime()-(3*24*60*60*1000));
			dateV = new Date(date.getTime()+(4*24*60*60*1000));
			break;
		case "Mindent":
			dateK = new Date(0);
			dateV = new Date(2100,1);
			break;
	}
}
dateK.setHours(0);
dateK.setMinutes(0);
dateV.setHours(0);
dateV.setMinutes(0);

function elozohet(){
	dateK = new Date(dateK.getTime()-(7*24*60*60*1000));
	dateV = new Date(dateV.getTime()-(7*24*60*60*1000));
	kiir();
}
function kovetkezohet(){
	dateK = new Date(dateK.getTime()+(7*24*60*60*1000));
	dateV = new Date(dateV.getTime()+(7*24*60*60*1000));
	kiir();
}

function szabadhelyek(v){
	let foglalt=0;
	for(let i=0;i<v.foglalasok.length;i++){
		foglalt+=Number(v.foglalasok[i][1]);
	}
	return (v.helyek-foglalt);
}

function kiir(){
	document.getElementsByTagName("h3")[0].innerHTML=
	dateK.getFullYear()+"-"+('0'+(dateK.getMonth()+1)).slice(-2)+"-"+('0'+dateK.getDate()).slice(-2)+" és "+
	dateV.getFullYear()+"-"+('0'+(dateV.getMonth()+1)).slice(-2)+"-"+('0'+dateV.getDate()).slice(-2)+" között";
	document.getElementById("tabla").innerHTML="";
	let row=document.createElement("tr");
	row.innerHTML='\
		<tr><th>Nap</th><th>Időpont</th>\
		<th>Film címe</th>\
		<th>Terem neve</th>\
		<th>Nyelv</th>\
		<th>Helyek</th>\
		<th>Jegyvásárlás</th>';
	<?php
	if(isset($_SESSION['user']) && $_SESSION['user']->email=="admin@mozi.hu"){
		echo "row.innerHTML+='<th>Módosítás</th>';";
	}
	?>
	document.getElementById("tabla").appendChild(row);
	
	var vetitesek;
	var r = new XMLHttpRequest();
	r.open('GET','vetitesek.txt');
	r.onload = function(){
		vetitesek=JSON.parse(r.responseText);
		function cmp(a, b){
			let str=a.datum.split(" ")[0]+"T"+a.datum.split(" ")[1];
			let dateA=new Date(str);
			str=b.datum.split(" ")[0]+"T"+b.datum.split(" ")[1];
			let dateB=new Date(str);
			return dateA.getTime() - dateB.getTime();
		}
		vetitesek.sort(cmp);
		
		for(let i=0;i<vetitesek.length;i++){
			let str=vetitesek[i].datum.split(" ")[0]+"T"+vetitesek[i].datum.split(" ")[1];
			let datumVet=new Date(str);
			if(datumVet > dateK && datumVet < dateV){
				if(vetitesek[i].aktiv || (!vetitesek[i].aktiv && document.getElementById("mail").innerHTML=="admin@mozi.hu")){
					row=document.createElement("tr");
					let cell=document.createElement("td");
					cell.innerHTML=vetitesek[i].datum.split(" ")[0];
					row.appendChild(cell);
					cell=document.createElement("td");
					cell.innerHTML=vetitesek[i].datum.split(" ")[1];
					row.appendChild(cell);
					cell=document.createElement("td");
					cell.innerHTML=vetitesek[i].cim;
					row.appendChild(cell);
					cell=document.createElement("td");
					cell.innerHTML=vetitesek[i].terem;
					row.appendChild(cell);
					cell=document.createElement("td");
					cell.innerHTML=vetitesek[i].nyelv;
					row.appendChild(cell);
					cell=document.createElement("td");
					let str=szabadhelyek(vetitesek[i]) + "/" + vetitesek[i].helyek;
					cell.innerHTML=str;
					if(vetitesek[i].cim=="Bejutás Web2-re"){
						cell.innerHTML+="<br>(Nem bővíthető)";
					}
					row.appendChild(cell);
					cell=document.createElement("td");
					if(vetitesek[i].aktiv && jegygomb(vetitesek[i])){
						cell.innerHTML="<a href='jegyvasarlas.php?vetites=" + vetitesek[i].id + "'><button>Jegyvásárlás</button></a>";
					}else if(vetitesek[i].aktiv){
						cell.innerHTML="Nem elérhető";
					}else if(!vetitesek[i].aktiv && document.getElementById("mail").innerHTML=="admin@mozi.hu"){
						cell.innerHTML="Inaktív";
					}
					row.appendChild(cell);
					
					if(document.getElementById("mail").innerHTML=="admin@mozi.hu"){
						cell=document.createElement("td");
						cell.innerHTML="<a href='vetitesmodositas.php?vetites="+vetitesek[i].id+"'><button>Módosítás</button></a>";
						row.appendChild(cell);
					}
					if(!jegygomb(vetitesek[i])){
						row.style.color = "dimgray";
					}
					if(document.getElementById("mail").innerHTML=="admin@mozi.hu" && !vetitesek[i].aktiv){row.style.color = "purple";}
					document.getElementById("tabla").appendChild(row);
				}
			}
		}
	};
	r.send();
}

function jegygomb(v){
	var now = new Date();
	var datum = new Date(v.datum.split(" ")[0]+"T"+v.datum.split(" ")[1].split(":")[0]+":"+v.datum.split(" ")[1].split(":")[1]);
	datum = new Date(datum.getTime()-(60*60*1000));

	return datum>now && (szabadhelyek(v)>0);
};



window.addEventListener('load', kiir);
</script>
</body></html>