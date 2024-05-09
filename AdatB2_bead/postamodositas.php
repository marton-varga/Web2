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

<?php include "header.php"; ?>
<main><h1>Posta módosítása</h1>

<div class="box">
	<p>Fejlesztés alatt...</p>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>