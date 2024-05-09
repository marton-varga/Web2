var can = true;
var won = false;
var player=1;
var matrix = [];

var hoverkorongpos;

var player1, player2;
var p1win=0;
var p2win=0;
var draws=0;
var pairId=0;

var timelimit;
var Interval;
var timecounter;
var timecountdown;

var p2robot;

var saveId=-1;
var doge=false;

//ONLOAD

function main(){
	setstyle();
	document.getElementById("start").addEventListener('click', ujjatek, false);
	document.getElementById("menugomb").addEventListener('click', menu, false);
	
	document.getElementById("jatekostabla").addEventListener('click', setplayersE, false);
	document.getElementById("jatekszabaly").addEventListener('click', jatekszabaly, false);
	document.getElementById("szabalykilep").addEventListener('click', kilep, false);
	document.getElementById("mentesek").addEventListener('click', mentes, false);
	document.getElementById("menteskilep").addEventListener('click', kilep, false);
	
	document.getElementById("gep").addEventListener('click', gepcheck, false);
	document.getElementById("idolimit").addEventListener('click', idolimitcheck, false);
	idolimitcheck();
	gepcheck();
	savestart();
	loadplayers();
	loadsaves();
	load();
	
	//maintabla
	document.getElementById('touchtable').addEventListener('mousedown', click, false);
	
	//document.getElementById('touchtable').addEventListener('mouseover', hoverkorong, false);
	document.getElementById('touchtable').addEventListener('mousemove', mousesmoothen, false);
	
	window.addEventListener('keydown', key, false);
	
	document.getElementById("ujjatek").addEventListener('click', ujjatek, false);
	document.getElementById("visszamenu").addEventListener('click', menu, false);
	
	document.getElementById("mentes").addEventListener('click', savegame, false);
}

function load(){
	let counter=0;
	for(let i=0;i<6;i++){
		matrix[i]=new Array(7);
		for(let j=0;j<7;j++){
			matrix[i][j]=0;
			counter++;
		}
	}
}

//MENU

function menu(){
	document.getElementById("menu").style.display="block";
	document.getElementById("game").style.display="none";
	loadplayers();
	loadsaves();
}

function start(){
	saveplayers();
	if(timelimit){
		clearInterval(Interval);
		Interval=window.setInterval(katt, 1000);
		timecountdown=timecounter;
	}

	for(let i=1;i<5;i++){
		if(document.getElementById("win"+i)){
			document.getElementById("win"+i).parentElement.innerHTML="";
		}
	}
	can=true;
	won=false;
	document.getElementById("winrow").style.display="none";
	document.querySelector("#winrow p").innerHTML="";
	hoverkorongpos=3;
	hoverkorongmove(hoverkorongpos);

	document.getElementById("menu").style.display="none";
	document.getElementById("game").style.display="block";
	maintabla();
}

function ujjatek(){
	saveId=-1;
	let counter=0;
	for(let i=0;i<6;i++){
		for(let j=0;j<7;j++){
			document.querySelectorAll("#contenttable tr td div")[counter].setAttribute("class", "korong0");
			matrix[i][j]=0;
			counter++;
		}
	}
	let p=hoverP();
	document.getElementById("hoverkorong"+p).setAttribute("id", "hoverkorong1");
	player=1;
	start();
}

function loadgame(sId){
	saveId=sId;
	p=hoverP();
	player=Number(localStorage.getItem("save_player"+sId));
	document.getElementById("hoverkorong"+p).setAttribute("id", "hoverkorong"+player);
	pairId=localStorage.getItem("save_pairId"+sId);
	setplayers();
	let str=localStorage.getItem("save_game"+sId);
	let counter=0;
	for(let i=0;i<6;i++){
		for(let j=0;j<7;j++){
			document.querySelectorAll("#contenttable tr td div")[counter].setAttribute("class", "korong"+str[counter]);
			matrix[i][j]=Number(str[counter]);
			counter++;
		}
	}
	start();
	kilep();
}

function jatekszabaly(){
	document.getElementById("mentesoldal").style.display="none";
	messageanimate("szabaly");
}

function mentes(){
	document.getElementById("szabaly").style.display="none";
	messageanimate("mentesoldal");
}

function kilep(){
	document.getElementById("szabaly").style.display="none";
	document.getElementById("mentesoldal").style.display="none";
}

function gepcheck(){
		if(document.getElementById("gep").checked){
			p2robot=true;
		}else{
			p2robot=false;
		}
}

//TIMELIMIT

function idolimitcheck(){
	if(document.getElementById("idolimit").checked){
		document.getElementById("idolimitnum").disabled=false;
		timecounter=document.getElementById("idolimitnum").value;
		timecountdown=0;
		timelimit=true;
		Interval=window.setInterval(katt, 1000);
	}else{
		document.getElementById("counter").innerHTML="";
		document.getElementById("idolimitnum").disabled=true;
		timelimit=false;
		clearInterval(Interval);
	}
}

function katt(){
	if(!won){
		document.getElementById("counter").innerHTML=timecountdown;
		if(timecountdown>0){
			timecountdown--;
		}
		else if(timecountdown==0){
			if(player==1){player=2;}
			else{player=1;}
			clearInterval(Interval);
			timecountdown=timecounter;
			win(0, 0, 0);
		}
	}
}

//NAME + SAVE

function saveplayers(){
	player1=document.getElementById("player1").value;
	player2=document.getElementById("player2").value;
	if(player1===""){player1="1. játékos";}
	if(player2===""){player2="2. játékos";}
	let numofplayers=localStorage.getItem("numofplayers");
	let i=0;
	while(i<numofplayers && !(localStorage.getItem("savedplayer1_"+i)==player1 && localStorage.getItem("savedplayer2_"+i)==player2)){
		i++;
	}
	if(i==localStorage.getItem("numofplayers")){
		localStorage.setItem("savedplayer1_"+numofplayers, player1);
		localStorage.setItem("savedplayer2_"+numofplayers, player2);
		localStorage.setItem("savedwin1_"+numofplayers, 0);
		localStorage.setItem("savedwin2_"+numofplayers, 0);
		localStorage.setItem("savedwinT_"+numofplayers, 0);
		numofplayers++;
		localStorage.setItem("numofplayers", numofplayers);
	}
	pairID=i;
	setplayers();
}

function setplayers(){
	player1=localStorage.getItem("savedplayer1_"+pairId);
	player2=localStorage.getItem("savedplayer2_"+pairId);
	p1win=localStorage.getItem("savedwin1_"+pairId);
	p2win=localStorage.getItem("savedwin2_"+pairId);
	draws=localStorage.getItem("savedwinT_"+pairId);
	document.getElementById("player1").value=localStorage.getItem("savedplayer1_"+pairId);
	document.getElementById("player2").value=localStorage.getItem("savedplayer2_"+pairId);
}

function loadplayers(){
	let numofplayers=localStorage.getItem("numofplayers");
	let content="<caption>Eddigi játékosok</caption><tr id='uH'><th>1. játékos</th><th>2. játékos</th><th>1. nyert</th><th>2. nyert</th><th>döntetlen</th></tr>";
	for(let i=0;i<numofplayers;i++){
		content+="<tr id='u"+i+"'>";
		content+="<td>"+localStorage.getItem("savedplayer1_"+i)+"</td>";
		content+="<td>"+localStorage.getItem("savedplayer2_"+i)+"</td>";
		content+="<td>"+localStorage.getItem("savedwin1_"+i)+"</td>";
		content+="<td>"+localStorage.getItem("savedwin2_"+i)+"</td>";
		content+="<td>"+localStorage.getItem("savedwinT_"+i)+"</td>";
		content+="</tr>";
	}
	document.getElementById("jatekostabla").innerHTML=content;
}

function setplayersE(e){
	if(e.target.parentElement.id!="jatekostabla" && e.target.parentElement.id[1]!="H"){
		pairId=e.target.parentElement.id[1];
		setplayers();
	}
}

function loadsaves(){
	let numofsaves=localStorage.getItem("numofsaves");
	document.getElementById("mentestabla").innerHTML="<caption>Mentett játékok</caption><tr><th>Dátum</th><th>Állapot</th></tr>";
	for(let i=0;i<numofsaves;i++){
		let content="<tr id='s"+i+"'>"
		let tmp=localStorage.getItem("save_date"+i);
		content+="<td>"+tmp+"</td>";
		tmp=localStorage.getItem("save_pct"+i);
		content+="<td>"+tmp+"</td>";
		content+="<td><input type='button' value='Betölt' onclick='loadgame("+i+")'><input type='button' value='Törlés' onclick='savedelete("+i+")'></td>"
		content+="</tr>"
		document.getElementById("mentestabla").innerHTML+=content;	
	}
}

function savegame(){
	if(!won){
		let numofsaves=localStorage.getItem("numofsaves");
		let date=dateFormat();
		
		let counter=42-countmatrix0();
		let pct=100*(counter/42);
		pct=pct.toFixed(0);
		pct=String(pct)+"%";
		
		let game=matrixToString();
			
		localStorage.setItem("save_date"+numofsaves, date);
		localStorage.setItem("save_pct"+numofsaves, pct);
		localStorage.setItem("save_pairId"+numofsaves, pairId);
		localStorage.setItem("save_game"+numofsaves, game);
		localStorage.setItem("save_player"+numofsaves, player);
		numofsaves++;
		localStorage.setItem("numofsaves", numofsaves);
		menu();
	}
}

function savedelete(sId){
	let numofsaves=localStorage.getItem("numofsaves");
	localStorage.removeItem("save_date"+sId);
	localStorage.removeItem("save_pct"+sId);
	localStorage.removeItem("save_pairId"+sId);
	localStorage.removeItem("save_player"+sId);
	for(let i=sId;i<numofsaves-1;i++){
		let tmp=localStorage.getItem("save_date"+(i+1));
		localStorage.setItem("save_date"+i, tmp);
		tmp=localStorage.getItem("save_pct"+(i+1));
		localStorage.setItem("save_pct"+i, tmp);
		tmp=localStorage.getItem("save_pairId"+(i+1));
		localStorage.setItem("save_pairId"+i, tmp);
		tmp=localStorage.getItem("save_game"+(i+1));
		localStorage.setItem("save_game"+i, tmp);
		tmp=localStorage.getItem("save_player"+(i+1));
		localStorage.setItem("save_player"+i, tmp);
	}
	numofsaves--;
	localStorage.setItem("numofsaves", numofsaves);
	loadsaves();
	saveId=-1;
}

function savestart(){
	if(!(localStorage.getItem("numofplayers"))){
		localStorage.setItem("numofplayers", 0);
	}
	if(!(localStorage.getItem("numofsaves"))){
		localStorage.setItem("numofsaves", 0);
	}
}

function matrixToString(){
	let str="";
	for(let i=0;i<6;i++){
		for(let j=0;j<7;j++){
			str+=String(matrix[i][j]);
		}
	}
return str;}

function dateFormat(){
	let d=new Date;
	let date=d.getFullYear();
	date+="-";
	if(String(d.getMonth()).length<2){
		date+="0";
	}
	date+=d.getMonth();
	date+="-";
	if(String(d.getDate()).length<2){
		date+="0";
	}
	date+=d.getDate();
	date+=" ";
	
	if(String(d.getHours()).length<2){
		date+="0";
	}
	date+=d.getHours();
	date+=":";
	if(String(d.getMinutes()).length<2){
		date+="0";
	}
	date+=d.getMinutes();
	date+=":";
	if(String(d.getSeconds()).length<2){
		date+="0";
	}
	date+=d.getSeconds();
return date;}

//LAYOUT&CONTROL

function maintabla(){
	document.getElementById("p1name").innerHTML=player1 + ": " + p1win;
	document.getElementById("p2name").innerHTML=player2 + ": " + p2win;
	document.getElementById("draws").innerHTML="Döntetlen: " + draws;

	timecounter=document.getElementById("idolimitnum").value;
	timecountdown=timecounter;
}

function hoverP(){
	let p;
	if(document.getElementById("hoverkorong1")){p=1;}
	else if(document.getElementById("hoverkorong2")){p=2;}
	else{p=0;}
return p;}

function hoverkorongmove(j, neg=false){
	let p=hoverP();
	if(document.getElementById("hoverkorong"+p)){
		if(!neg){
			let a = document.getElementById("hoverkorong"+p).getBoundingClientRect().left;
			document.getElementById("hoverkorong"+p).parentElement.innerHTML = "";
			document.getElementById("h"+j).innerHTML="<div id='hoverkorong"+p+"'></div>";
			let b = document.getElementById("hoverkorong"+p).getBoundingClientRect().left;
			let plus;
			let distance=-(b-a);
			if(b<a){plus="-";}
			else{plus="";}
			document.getElementById("hoverkorong"+p).animate([
				{transform: 'translateX('+distance+'px)'},
				{transform: 'translateX('+plus+'1vw)'},
				{transform: 'translateX(0px)'}
				],
				{duration: 200, iteration: 1},
			);
		}else{
			document.getElementById("hoverkorong"+p).animate([
			{transform: 'translateX(0px)'},
			{transform: 'translateX(-1vw)'},
			{transform: 'translateX(0px)'}
			],
			{duration: 200, iteration: 1},
		);
		}
	}
}

function hoverkorong(e){
	if(e.target.id!=="touchtable"){
		hoverkorongpos=e.target.id[1];
	}
	if(e.target.id!=="touchtable" && can && !won && !(player==2 && p2robot)){
		let j=e.target.id[1];
		hoverkorongmove(j);
	}
}

function drop(j){
	i=6;
	let van=false;
	while(i >= 1 && !van){
		i--;
		if(matrix[i][j] == 0){
			document.getElementById("c"+i+""+j).children[0].setAttribute("class", "korong"+player);
			matrix[i][j]=player;
			animatefall(i, j);
			switchplayer();
			van=true;
		}
	}
	check();
}

function click(e){
	if(won===false && can===true && e.target.id!=="touchtable" && matrix[0][Number(e.target.id[1])]==0 && !(player==2 && p2robot)){
		can=false;
		p=hoverP();
		if(document.getElementById("hoverkorong"+p).parentElement.id[1] != e.target.id[1]){
			hoverkorongpos=e.target.id[1];
			hoverkorongmove(hoverkorongpos);
		}
		let j=Number(e.target.id[1]);
		setTimeout(function(){drop(j);}, 300);
		document.getElementById('touchtable').removeEventListener('mouseover', hoverkorong, false);
		document.getElementById('touchtable').addEventListener('mouseover', hoverkorong, false);
	}
}

function key(e){
	let p=hoverP();
	//let j=Number(document.getElementById("hoverkorong"+p).parentElement.id[1]);
	let j=hoverkorongpos;
		
	//enter, space
	if(!(player==2 && p2robot)){
		if(e.which===13 || e.which===32){
			if(won===false && can===true && matrix[0][j]==0){
				can=false;
				drop(j);
			}
		}
	}
		
	//bal
	if(e.which===37){
		let left=(j==0);
		if(j!=0){
			j--;	
		}
		hoverkorongpos=j;
		if(!(player==2 && p2robot)){
			hoverkorongmove(j, left);
		}
	}
	//jobb
	else if(e.which===39){
		if(j!=6){
			j++;
		}
		hoverkorongpos=j;
		if(!(player==2 && p2robot)){
			hoverkorongmove(j);
		}
	}
}

function animatefall(j, i){
	let d=100*(j+1);
	if(doge){document.getElementById("c"+j+""+i).children[0].style.zIndex="1";}
	let distance = (document.getElementById("c"+j+""+i).children[0].getBoundingClientRect().top)-(document.getElementById("hoverkorong"+player).getBoundingClientRect().top);
	document.getElementById("hoverkorong"+player).setAttribute("id", "hoverkorong0");
	document.getElementById("c"+j+""+i).children[0].animate([
	{transform: 'translateY(-'+distance+'px)'},
	{transform: 'translateY(0px)'}
	],
	{duration: d, iteration: 1},
	);
	setTimeout(function(){hoverkorongmove(hoverkorongpos);}, d);
	setTimeout(function(){if(doge){document.getElementById("c"+j+""+i).children[0].style.zIndex="3";}}, (d+100));
}

function switchplayer(){
	timecountdown=timecounter;
	let interval = null;
	let hw = 84;
	clearInterval(interval);
	interval = setInterval(frame1, 1);
	function frame1(){
		if(hw>0){
			hw-=4; 
			document.getElementById("hoverkorong0").style.width = hw + "%";
		}else{
			if(player===1){
				player=2;
				document.getElementById("hoverkorong0").setAttribute("id", "hoverkorong2");
			}
			else{
				player=1;
				document.getElementById("hoverkorong0").setAttribute("id", "hoverkorong1");
			}
			can = true;
			clearInterval(interval);
			interval = setInterval(frame2, 1);
			function frame2(){
				if(hw<84){
					hw+=4;
					if(document.getElementById("hoverkorong"+player)){
						document.getElementById("hoverkorong"+player).style.width = hw + "%";
					}
				}else{
					clearInterval(interval);
				}
			}
			if(won){
				p=hoverP();
				if(document.getElementById("hoverkorong"+p)){
					document.getElementById("hoverkorong"+p).setAttribute("id", "hoverkorong0");
				}
			}else if(player==2 && p2robot){
				AImain();
			}
		}
	}
}

function messageanimate(id){
	document.getElementById(id).style.opacity = 0;
	let interval = null;  
	let hw = 0;
	clearInterval(interval);
	interval = setInterval(frame1, 10);
	function frame1(){
		if(hw<=10){
			hw++; 
			document.getElementById(id).style.opacity = (hw/10);
		}else{
			clearInterval(interval);
		}
	}
	document.getElementById(id).style.display="block";
	document.getElementById(id).animate([
	{transform: 'translateY(-10%)'},
	{transform: 'translateY(0px)'}
	],
	{duration: 200, iteration: 1},
	);
}

function mousesmoothen(e){
	let p=hoverP();
	if(!(player==2 && p2robot==true) && e.target.id!=="touchtable" && e.target.id[1]!=document.getElementById("hoverkorong"+p).parentElement.id[1]){
		setTimeout(60);
		let j=Number(e.target.id[1]);
		hoverkorongpos=j;
		hoverkorongmove(j);
	}
}

//GAME

function check(){
	let p=player;
	let irany=0;
	let iranyok=[0,0,0,0];
	let a, b;
	let i=0;
	let j;
	while(i<=5){
		j=0;
		while(j<=6){
			if(matrix[i][j]==p){
				let k=0;
				while(iranyok[0]===0 && j+k<=6 && matrix[i][j+k]==p && k<4){
					k++;
				}
				if(k==4){
					irany=1;
					iranyok[0]=1;
					winanimate(i,j,1);
				}
				k=0;
				while(iranyok[1]===0 && i+k<=5 && matrix[i+k][j]==p && k<4){
					k++;
				}
				if(k==4){
					irany=2;
					iranyok[1]=1;
					winanimate(i,j,2);
				}
				k=0;
				while(iranyok[2]===0 && i+k<=5 && j+k<=6 && matrix[i+k][j+k]==p && k<4){
					k++;
				}
				if(k==4){
					irany=3;
					iranyok[2]=1;
					winanimate(i,j,3);
				}
				k=0;
				while(iranyok[3]===0 && i+k<=5 && j-k>=0 && matrix[i+k][j-k]==p && k<4){
					k++;
				}
				if(k==4){
					irany=4;
					iranyok[3]=1;
					winanimate(i,j,4);
				}
				k=0;
			}
			j++;
		}
		i++;
	}
	if(irany>0){
		win();
	}
	if(!won && countmatrix0()===0){
		tie();
	}
}

function win(){
	won=true;
	
	if(player===1){
		document.querySelector("#winrow p").innerHTML= player1 + " nyert!";
		p1win++;
		localStorage.setItem("savedwin1_"+pairId, p1win);
	}
	else{
		document.querySelector("#winrow p").innerHTML= player2 + " nyert!";
		p2win++;
		localStorage.setItem("savedwin2_"+pairId, p2win);
	}
	messageanimate("winrow");
	if(saveId!=-1){
		savedelete(saveId);
	}
}

function winanimate(i,j, irany){
	let p=player;
	document.querySelector("#toptable table").getElementsByTagName("tr")[i].getElementsByTagName("td")[j].innerHTML+="<div id='win"+irany+"'></div>";
	let k=0;
	if(irany===1){
		while(j+k<=6 && matrix[i][j+k]==p){
			k++;
		}
	}
	else if(irany===2){
		while(i+k<=5 && matrix[i+k][j]==p){
			k++;
		}
	}
	else if(irany===3){
		while(i+k<=5 && j+k<=6 && matrix[i+k][j+k]==p){
			k++;
		}
	}
	else if(irany===4){
		while(i+k<=5 && j-k>=0 && matrix[i+k][j-k]==p){
			k++;
		}
	}
	if(irany==1){
		k=(100*k)-50+((k-4)*4);
	}
	else if(irany==2){
		k=k*84;
		document.getElementById("win"+irany).style.transform="rotate(90deg)";
		document.getElementById("win"+irany).style.transformOrigin="top left";
		document.getElementById("win"+irany).style.left="60%";
		document.getElementById("win"+irany).style.top="-16%";
	}
	else if(irany==3){
		k=(k*128)-50+((k-4)*18);
		document.getElementById("win"+irany).style.transform="rotate(42deg)";
		document.getElementById("win"+irany).style.transformOrigin="top left";
		document.getElementById("win"+irany).style.left="42%";
		document.getElementById("win"+irany).style.top="-10%";
	}
	else if(irany==4){
		k=(k*128)-30+((k-4)*18);
		document.getElementById("win"+irany).style.transform="rotate(137deg)";
		document.getElementById("win"+irany).style.transformOrigin="top left";
		document.getElementById("win"+irany).style.left="73%";
		document.getElementById("win"+irany).style.top="-5%";
	}
	let interval = null;
	let hw=0;
	clearInterval(interval);
	interval = setInterval(frame1, 1);
	function frame1(){
		if(hw<k){
			hw+=8;
			if(document.getElementById("win"+irany)){
				document.getElementById("win"+irany).style.width = hw + "%";
			}
		}else{
			clearInterval(interval);
		}
	}
}

function tie(){
	won=true;
	document.querySelector("#winrow p").innerHTML= "Döntetlen!";
	draws++;
	localStorage.setItem("savedwinT_"+pairId, draws);
	messageanimate("winrow");
	if(saveId!=-1){
		savedelete(saveId);
	}
}

function countmatrix0(){
	let counter=0;
	for(let i=0;i<6;i++){
		for(let j=0;j<7;j++){
			if(matrix[i][j]===0){
				counter++;
			}
		}
	}
return counter;}

//AI

function AImain(){
	let j=AIdecide();
	AImove(j);
}

function AImove(j){
	let time=2000;
	if(timelimit && timecounter<6){
		time=timecounter*200;
	}
	setTimeout(function(){hoverkorongmove(j);}, time);
	time=time*1.4;
	setTimeout(function(){drop(j);}, time);
	time=time+(100*(j+1))+250;
}

function AIdecide(){
	let p=player;
	let o; if(player==1){o=2;}else{o=1;}
	let j;
	
	let valid=AIcanIwin(p);
	let validhelp=AIcanIhelpwin(o);
	
	if(cntvalid(valid)>0){
		j=AIrandom(valid);
		return j;
	}else{
		valid=AIcanIwin(o);
		if(cntvalid(valid)>0){
			j=AIrandom(valid);
			return j;
		}
		else{
			valid=AIdoIsee2(p);
			if(cntvalid(valid)>0){
				validhelp=AIcanIhelpwin(o);
				if(cntvalid(AIdontwannahelp(valid, validhelp))>0){
					valid=AIdontwannahelp(valid, validhelp);
				}
				j=AIrandom(valid);
				return j;
			}else{
				valid=AIdoIsee2(o);
				validhelp=AIcanIhelpwin(o);
				if(cntvalid(AIdontwannahelp(valid, validhelp))>0){
					valid=AIdontwannahelp(valid, validhelp);
				}
				if(cntvalid(valid)>0){
					j=AIrandom(valid);
					return j;
				}else{
					
					valid=AIwherevalid();
					
					validhelp=AIcanIhelpwin(o);
					if(cntvalid(AIdontwannahelp(valid, validhelp))>0){
						valid=AIdontwannahelp(valid, validhelp);
					}
					validhelp=AIdoIsee2help(o);
					if(cntvalid(AIdontwannahelp(valid, validhelp))>0){
						valid=AIdontwannahelp(valid, validhelp);
					}
					if(cntvalid(valid)>0){
					j=AIrandom(valid);
					return j;
					}else{
						j=AIrandomMove();
						return j;
					}
				}
			}
		}
	}
}

function AIrandom(valid){
	for(let l=0;l<7;l++){
		if(matrix[0][l]!=0){valid[l]=false;}
	}
	if(cntvalid(valid)==0){
		for(let l=0;l<7;l++){
			if(matrix[0][l]==0){valid[l]=true;}
		}
	}
	moves=cntvalid(valid);
	let randomMove = Math.floor(Math.random()*moves);
	while(!valid[randomMove]){
		randomMove++;
	}
return randomMove;}

function AIrandomMove(){
	let moves=0;
	for(let i=0;i<7;i++){
		if(matrix[0][i]==0){
			moves++;
		}
	}
	let randomMove = Math.floor(Math.random()*moves);
	let ok=false;
	while(!ok){
		if(matrix[0][randomMove]!=0){
			randomMove++;
		}else{
			ok=true;
		}
	}
return randomMove;}

function AIcanIwin(p){
	let moves=AIwhere();
	let valid = [false, false, false, false, false, false, false];
	for(let j=0;j<7;j++){
		let i=moves[j];
		let k=1;
		while(i+k<6 && i>=0 && matrix[i+k][j]==p){k++;}
		if(k>3){valid[j]=true;}
		
		k=1; let van=0;
		while(j-k>=0 && i>=0 && matrix[i][j-k]==p){k++;van++;}
		k=1;
		while(j+k<7 && i>=0 && matrix[i][j+k]==p){k++;van++;}
		if(van>2){valid[j]=true;}
		
		k=1; van=0;
		while(j-k>=0 && i-k>=0 && matrix[i-k][j-k]==p){k++;van++;}
		k=1;
		while(j+k<7 && i+k<6 && matrix[i+k][j+k]==p){k++;van++;}
		if(van>2){valid[j]=true;}
		
		k=1; van=0;
		while(j+k<7 && i-k>=0 && matrix[i-k][j+k]==p){k++;van++;}
		k=1;
		while(j-k>=0 && i+k<6 && matrix[i+k][j-k]==p){k++;van++;}
		if(van>2){valid[j]=true;}
	}
	for(let l=0;l<7;l++){
		if(matrix[0][l]!=0){valid[l]=false;}
	}
return valid;}

function AIcanIhelpwin(p){
	let moves=AIwhere();
	let valid = [false, false, false, false, false, false, false];
	for(let j=0;j<7;j++){
		let i=moves[j];
		if(i>1){
			i--;

			let k=1;
			let van=0;
			while(j-k>=0 && i>=0 && matrix[i][j-k]==p){k++;van++;}
			k=1;
			while(j+k<7 && i>=0 && matrix[i][j+k]==p){k++;van++;}
			if(van>2){valid[j]=true;}
			
			k=1; van=0;
			while(j-k>=0 && i-k>=0 && matrix[i-k][j-k]==p){k++;van++;}
			k=1;
			while(j+k<7 && i+k<6 && matrix[i+k][j+k]==p){k++;van++;}
			if(van>2){valid[j]=true;}
			
			k=1; van=0;
			while(j+k<7 && i-k>=0 && matrix[i-k][j+k]==p){k++;van++;}
			k=1;
			while(j-k>=0 && i+k<6 && matrix[i+k][j-k]==p){k++;van++;}
			if(van>2){valid[j]=true;}
		}
	}
	for(let l=0;l<7;l++){
		if(matrix[0][l]!=0){valid[l]=false;}
	}
return valid;}

function AIdoIsee2(p){
	let moves=AIwhere();
	let valid = [false, false, false, false, false, false, false];
	for(let j=1;j<6;j++){
		let i=moves[j];
		
		let left=1; let right=1;
		while(j-left>=0 && i>=0 && matrix[i][j-left]==p){left++;}
		if(j-left>=0 && i>=0 && left>2 && matrix[i][j-left]===0 && (i+1==6 || matrix[i+1][j-left]!==0)){valid[j]=true;}	
		while(j+right<7 && i>=0 && matrix[i][j+right]==p){right++;}
		if(j+right<7 && i>=0 && right>2 && matrix[i][j+right]===0 && (i+1==6 || matrix[i+1][j+right]!==0)){valid[j]=true;}
		if(j-left>=0 && j+right<7 && i>=0 && left+right>3 && matrix[i][j-left]===0 && matrix[i][j+right]===0 && (i+1==6 || (matrix[i+1][j-left]!==0 && matrix[i+1][j+right]!==0))){valid[j]=true;}	
		
		left=1; right=1;
		while(j-left>=0 && i-left>=0 && matrix[i-left][j-left]==p){left++;}
		if(i<5 && j-left>=0 && i-left>=0 && left>2 && matrix[i-left][j-left]===0 && (matrix[i-left+1][j-left]!==0)){valid[j]=true;}
		while(j+right<7 && i+right<6 && matrix[i+right][j+right]==p){right++;}
		if(i>=1 && j+right<7 && i+right<6 && right>2 && matrix[i+right][j+right]===0 &&(i+right+1==6 || matrix[i+right+1][j+right]!==0)){valid[j]=true;}
		if(j-left>=0 && i-left>=0 && j+right<7 && i+right<6 && left+right>3 && matrix[i-left][j-left]===0 && matrix[i+right][j+right]===0 && (matrix[i-left+1][j-left]!==0 && (i+right+1==6 || matrix[i+right+1][j+right]!==0))){valid[j]=true;}

		left=1; right=1;
		while(j-left>=0 && i+left<6 && matrix[i+left][j-left]==p){left++;}
		if(i>=1 && j-left>=0 && i+left<6 && left>2 && matrix[i+left][j-left]===0 && (i+left+1==6 || matrix[i+left+1][j-left]!==0)){valid[j]=true;}
		while(j+right<7 && i-right>=0 && matrix[i-right][j+right]==p){right++;}
		if(i<5 && j+right<7 && i-right>=0 && right>2 && matrix[i-right][j+right]===0 && (matrix[i-right+1][j+right]!==0)){valid[j]=true;}
		if(j-left>=0 && j+right<7 && i-right>=0 && i+left<6 && left+right>3 && matrix[i+left][j-left]===0 && matrix[i-right][j+left]===0 && (matrix[i-right+1][j+left]!==0 && (i+left+1==6 || matrix[i+left+1][j-right]!==0))){valid[j]=true;}
	}
	for(let l=0;l<7;l++){
		if(matrix[0][l]!=0){valid[l]=false;}
	}
return valid;}

function AIdoIsee2help(p){
	let moves=AIwhere();
	let valid = [false, false, false, false, false, false, false];
	for(let j=1;j<6;j++){
		let i=moves[j];
		if(i>1){
			i--;
			let left=1; let right=1;
			while(j-left>=0 && i>=0 && matrix[i][j-left]==p){left++;}
			if(j-left>=0 && i>=0 && left>2 && matrix[i][j-left]===0 && (i+1==6 || matrix[i+1][j-left]!==0)){valid[j]=true;}	
			while(j+right<7 && i>=0 && matrix[i][j+right]==p){right++;}
			if(j+right<7 && i>=0 && right>2 && matrix[i][j+right]===0 && (i+1==6 || matrix[i+1][j+right]!==0)){valid[j]=true;}
			if(j-left>=0 && j+right<7 && i>=0 && left+right>3 && matrix[i][j-left]===0 && matrix[i][j+right]===0 && (i+1==6 || (matrix[i+1][j-left]!==0 && matrix[i+1][j+right]!==0))){valid[j]=true;}	
			
			left=1; right=1;
			while(j-left>=0 && i-left>=0 && matrix[i-left][j-left]==p){left++;}
			if(i<5 && j-left>=0 && i-left>=0 && left>2 && matrix[i-left][j-left]===0 && (matrix[i-left+1][j-left]!==0)){valid[j]=true;}
			while(j+right<7 && i+right<6 && matrix[i+right][j+right]==p){right++;}
			if(i>=1 && j+right<7 && i+right<6 && right>2 && matrix[i+right][j+right]===0 &&(i+right+1==6 || matrix[i+right+1][j+right]!==0)){valid[j]=true;}
			if(j-left>=0 && i-left>=0 && j+right<7 && i+right<6 && left+right>3 && matrix[i-left][j-left]===0 && matrix[i+right][j+right]===0 && (matrix[i-left+1][j-left]!==0 && (i+right+1==6 || matrix[i+right+1][j+right]!==0))){valid[j]=true;}

			left=1; right=1;
			while(j-left>=0 && i+left<6 && matrix[i+left][j-left]==p){left++;}
			if(i>=1 && j-left>=0 && i+left<6 && left>2 && matrix[i+left][j-left]===0 && (i+left+1==6 || matrix[i+left+1][j-left]!==0)){valid[j]=true;}
			while(j+right<7 && i-right>=0 && matrix[i-right][j+right]==p){right++;}
			if(i<5 && j+right<7 && i-right>=0 && right>2 && matrix[i-right][j+right]===0 && (matrix[i-right+1][j+right]!==0)){valid[j]=true;}
			if(j-left>=0 && j+right<7 && i-right>=0 && i+left<6 && left+right>3 && matrix[i+left][j-left]===0 && matrix[i-right][j+left]===0 && (matrix[i-right+1][j+left]!==0 && (i+left+1==6 || matrix[i+left+1][j-right]!==0))){valid[j]=true;}
		}
		for(let l=0;l<7;l++){
			if(matrix[0][l]!=0){valid[l]=false;}
		}
	}
return valid;}

function AIdontwannahelp(valid, validhelp){
	for(let i=0;i<7;i++){
		if(validhelp[i]){
			valid[i]=false;
		}
	}
return valid;}

function AIwherevalid(){
	let moves=AIwhere();
	let valid=[false,false,false,false,false,false,false];
	for(let i=0;i<7;i++){
		if(moves[i]>-1){
			valid[i]=true;
		}
	}
return valid;}

function AIwhere(){
	let moves = [0,0,0,0,0,0,0];
	for(let j=0;j<7;j++){
		let i=0;
		while(i<6 && i>=0 && matrix[i][j]==0){
			i++;
		}
		i--;
	moves[j]=i;
	}
return moves;}

function cntvalid(valid){
	let cnt=0;
	for(let v=0;v<7;v++){
		if(valid[v]){cnt++;}
	}
return cnt;}

//STYLE

function setstyle(){
	if(localStorage.getItem("style")=="normal"){
		document.querySelector(':root').style=normalstyle;
		document.getElementById("stylegomb").value="Sötét stílus";
		localStorage.setItem("style", "normal");
	}else{
		document.querySelector(':root').style=darkstyle;
		document.getElementById("stylegomb").value="Normál stílus";
		localStorage.setItem("style", "dark");
	}
}

function switchstyle(){
	if(localStorage.getItem("style")=="normal"){
		document.querySelector(':root').style=darkstyle;
		document.getElementById("stylegomb").value="Normál stílus";
		localStorage.setItem("style", "dark");
	}else{
		document.querySelector(':root').style=normalstyle;
		document.getElementById("stylegomb").value="Sötét stílus";
		localStorage.setItem("style", "normal");
	}

}

function goDoge(){
	if(!doge){
		document.styleSheets[0].cssRules[4].style.cssText= "background-image: url('./sarga.png');background-repeat: no-repeat;background-size: contain, cover; z-index: 3;";
		document.styleSheets[0].cssRules[5].style.cssText= "background-image: url('./piros.png');background-repeat: no-repeat;background-size: contain, cover; z-index: 3;";
		for(let i=0;i<42;i++){
			document.getElementById("contenttable").getElementsByTagName("td")[i].children[0].style.zIndex="3";
		}
		document.getElementById("doge").value="Doge Off";
		doge=true;
	}else{
		document.styleSheets[0].cssRules[4].style.cssText= "background-image: radial-gradient(var(--yellow1), var(--yellow2), var(--yellow1)); z-index: 1;";
		document.styleSheets[0].cssRules[5].style.cssText= "background-image: radial-gradient(var(--red1), var(--red2), var(--red1)); z-index: 1;";
		for(let i=0;i<42;i++){
			document.getElementById("contenttable").getElementsByTagName("td")[i].children[0].style.zIndex="1";
		}
		
		document.getElementById("doge").value="Go Doge";
		doge=false;
	}
}

window.addEventListener('load', main, false);