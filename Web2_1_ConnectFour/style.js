const normalstyle=`
	--body: #FAFAD2;
	--fontcolor: black;
	
	--white: white;
	--steelblue: steelblue;

	--blue: blue;
	--mediumblue: mediumblue;
	--darkblue: darkblue;
	
	--yellow1: yellow;
	--yellow2: goldenrod;
	--red1: red;
	--red2: darkred;
	
	--grey: grey;
	--beige: beige;
	--gold: gold;
	--orange: orange;
`;

const darkstyle=`
	--body: #222222;
	--fontcolor: darksalmon;

	--white: black;
	--steelblue: #211612;

	--blue: #001a33;
	--mediumblue: #000d1a;
	--darkblue: #00051a;
	
	--yellow1: goldenrod;
	--yellow2: dimgray;
	--red1: crimson;
	--red2: black;
	
	--grey: dimgray;
	--beige: indigo;
	--gold: black;
	--orange: indigo;
`;

if(!localStorage.getItem("style")){
	localStorage.setItem("style", "normal");
}