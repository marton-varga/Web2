function modifyButtonClicked(e){
	let id=e.target.id.split('_')[1];
	let val;
	if(document.getElementById("modifyForm")){
		if(document.getElementById("modifyForm").children[0].value==id){
			val=document.getElementById("modifyForm").children[1].value;
			document.getElementById("modifyForm").parentElement.innerHTML=val;
			for(let i=0;i<document.getElementsByClassName("modifyButton").length;i++){
				document.getElementsByClassName("modifyButton")[i].innerHTML="Módosítás";
			}
			return;
		}
		val=document.getElementById("modifyForm").children[1].value;
		document.getElementById("modifyForm").parentElement.innerHTML=val;
		for(let i=0;i<document.getElementsByClassName("modifyButton").length;i++){
			document.getElementsByClassName("modifyButton")[i].innerHTML="Módosítás";
		}
	}
	val=document.getElementById("tr_"+id).children[1].innerHTML;
	e.target.innerHTML="Mégsem";
	document.getElementById("tr_"+id).children[1].innerHTML="<form id='modifyForm' action='' method='post'><input type='hidden' name='dij' value='"+id+"'><input type='number' style='width:4em;' min='1' value='"+val+"' name='ar'><input type='submit' value='OK'></form>";
}

for(let i=0;i<document.getElementsByClassName("modifyButton").length;i++){
	document.getElementsByClassName("modifyButton")[i].addEventListener('click',modifyButtonClicked,false);
}