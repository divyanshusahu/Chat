<?php
session_start();
if ($_SESSION['name'] == "")
header("Location:welcome.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>
	<link href="start.css" rel="stylesheet">
</head>
<body>
	<?php
	echo "<br><br><h1>" . "Welcome " . $_SESSION['name'] . "</h1>";	
	?><br>
	<div id="onlineperson">
	</div><br>
	<div id="display">
	</div>
	<br>
	<?php
		if((isset($_POST['send']) && isset($_POST['formid'])) && $_POST['formid']==$_SESSION['formid']) 
		{
			$fh = fopen('chat.txt',"a") or die("Unable to open file!");
			$msg = $_SESSION['name']." :".$_POST['message']."\n";
			fwrite($fh,$msg);
			fclose($fh);
			$_SESSION['formid']="";
		}
		else
			$_SESSION['formid']=md5(mt_rand());
	?>
	<div>
	<form action="" method="post" autocomplete="off">
		<input type="hidden" name="formid" value="<?php echo htmlspecialchars($_SESSION['formid']); ?>">
		<input type="text" class="form-control" required placeholder="Type a message" name="message" id="msg"><br>
		<input type="submit" value="Send" class="btn btn-primary btn-block btn-lg" name="send"> 
	</form>
	</div>
	<br><br><br>
	<form action="" method="post">
	<input type="submit" value="Logout" class="btn btn-danger" name="logout">
	</form>
	<?php
		if (isset($_POST['logout']))
		{
			$op = fopen('online.txt','r') or die ("Unable to open file");
			$msg = fgets($op);
			fclose($op);
			$p = $_SESSION['name'].", ";
			$msg = str_replace($p,"",$msg);
			$cl = fopen('online.txt','w') or die ("Unable to open file");
			fwrite($cl,$msg);
			fclose($cl);
			session_unset();
			session_destroy();
			header("Location:welcome.php");
		}
	?>
	<script src="start.js" rel="javascript"></script>
</body>
</html>