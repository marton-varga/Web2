<?php
session_start();
include "header.php"
?>

<main>
<div class="loginbox" id="siker">
	<h2>Sikeres jóváhagyás!</h2>
	<div>
		<button class="btn" onclick="cancel()">Vissza a főoldalra</button>
	</div>
</div>
</main>
<script>
function cancel(){
	window.location.href="index.php";
}
</script>
</body>
</html>