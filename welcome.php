<?php
	session_start();
	$nameofthesession=$_SESSION['name'];
	if ($nameofthesession)
		header("Location:start.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
	<link href="welcome.css" rel="stylesheet">
<body>
	<form action="" method="post" autocomplete="off">
	<h1 style="color:darkblue">Login</h1><br><br>
		<div class="form-group"><label><h2>Username : </h2></label><input type="text" name="luname" placeholder="Enter username"  required class="form-control"></div><br><br>
		<div class="form-group"><label><h2>Password : </h2></label><input type="password" name="lpassword" placeholder="Enter password"  required class="form-control"></div><br><br>
		<input type="submit" name="login" value="Login" class="btn btn-primary">
	</form>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunkometer";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 

$login = 0;
if (isset($_POST['login']))
{
	$uname = mysqli_real_escape_string($conn, $_POST['luname']);
	$password = mysqli_real_escape_string($conn, $_POST['lpassword']);
	$hashedpassword=md5($password);
	$sql1 = "SELECT username,password FROM users WHERE username  = '$uname' AND password = '$hashedpassword' ";
	/*$uname=$_POST['luname'];
	$password=$_POST['lpassword'];
	$sql1="SELECT username,password FROM users 	WHERE username='$uname' AND password='$password' ";*/
	$result1 = mysqli_query($conn,$sql1);
	if(mysqli_num_rows($result1)) 
	{
		$login = 1;
		$sqlname = "SELECT name FROM users WHERE username = '$uname' ";
		$resultname = mysqli_query($conn,$sqlname);
		$row = mysqli_fetch_assoc($resultname);
		$_SESSION['name']=$row['name'];	
	}
	else
	{
			echo "<br><h4 style='color:red ;'>Incorrect username or password</h4>";
			unset($uname);
			unset($password);
	}
	
}
if ($login ===1)
{
	header("Location:start.php");
	$_SESSION['formid']=md5("1");
	$online = fopen('online.txt','a') or die ("Unable to open file");
	$onlineperson = $_SESSION['name'] . ", ";
	fwrite($online,$onlineperson);
	fclose($online); 	
}
?>
	<br><br><br><br><br>
	<h1 style="color:green">Or register here</h1><br><br>
	<form action="" method="post" autocomplete="off">
		<div class="form-group"><label><h2>Name :</h2></label><input type="text" name="rname" required placeholder="Type your name" class="form-control"></div><br><br>
		<div class="form-group"><label><h2>Username :</h2></label><input type="text" name="runame" class="form-control" required placeholder="Type your username"></div><br><br>
		<div class="form-group"><label><h2>Password :</h2></label><input type="password" name="rpassword" class="form-control" required placeholder="Type your password"></div><br><br>
		<input type="submit" name="register" value="Register" class="btn btn-success">
	</form>

<?php
if (isset($_POST['register']))
{
	$name = $_POST['rname'];
	$uname = $_POST['runame'];
	$password = $_POST['rpassword'];
	$hashedpassword = md5($password);
	$sql = "INSERT INTO users 
	VALUES ('$name', '$uname','$hashedpassword')";

	if ($conn->query($sql) === FALSE) 
    	echo "<br><h4 style='color:red'>" ."Username already taken\n". "</h4>";
	else
	{
		$_SESSION['name']=$name;
		header("Location:start.php");
		$_SESSION['formid']=md5("1");
		$online = fopen('online.txt','a') or die ("Unable to open file");
		$onlineperson = $_SESSION['name'] . ", ";
		fwrite($online,$onlineperson);
		fclose($online);
	}
    
}


$conn->close();
?>
</body>
</html>