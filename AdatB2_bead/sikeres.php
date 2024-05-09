<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']==null){
	header("Location: ./login.php");
}
?>

<?php include "header.php"; ?>
<main>
<div class="box">
<p>A művelet sikeresen végrehajtva!<p>
<a href="index.php"><button>OK</button></a>
</div>
</main>
<script type="text/javascript" src="js.js"></script>
</body>
</html>