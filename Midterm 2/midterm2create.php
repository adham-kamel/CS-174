<?php
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die('error');
	echo <<<_END
	<html><body>
		<form action="midterm2create.php" method="post"><pre>
		CREATE ADMIN
		Username <input type="text" name="username">
		Password <input type="text" name="password">
		<input type="submit" value="Create Admin" name="submit">
		</pre></form>
_END;
	echo "</body></html>";

	if(isset($_POST['username']) && $_POST['password'])
	{
		$username = sanitizeMySQL($conn, $_POST['username']);
		$password = sanitizeMySQL($conn, $_POST['password']);

		$stmt = $conn->prepare('INSERT INTO adminTable VALUES'.'(?, ?, ?)');
		$salt = random();
		$hash = hash('ripemd128', "$salt$password");
		$stmt->bind_param('sss', $username, $hash, $salt);
		$stmt->execute();

		if($stmt->affected_rows == 0)die("error");
	}

	echo <<<_END
	<html><body>
		<form action="midterm2create.php" method="post"><pre>
		ADMIN LOGIN
		Username <input type="text" name="loginusername">
		Password <input type="text" name="loginpassword">
		<input type="submit" value="Log In" name="submit">
		</pre></form>
_END;
	echo "</body></html>";
	if(isset($_POST['loginusername']) && isset($_POST['loginpassword'])){
		$username = sanitizeMySQL($conn,$_POST['loginusername']);
        $password = sanitizeMySQL($conn,$_POST['loginpassword']);
        $stmt = $conn->prepare('SELECT * FROM adminTable WHERE username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if(!$result) die("Error");
        if ($result->num_rows > 0){
            $row = $result->fetch_array(MYSQLI_ASSOC);
			$salt = $row["salt"];
            $hash = hash('ripemd128', "$salt$password");
            if($hash == $row["password"]){
				start();
            }
            else{
                echo "Invalid username or password";
            }
        }
        else{
            echo "Invalid username or password";
		}
	}

	function start(){
		echo <<<_END
	<html><body>
		<form action="midterm2admin.php" method="post"><pre>
		Access Granted Hit Button to Continue
		<input type="submit" value="Start" name="submit">
		</pre></form>
_END;
	echo "</body></html>";
	}


	function sanitizeString($var) 
	{
		$var = stripslashes($var);
		$var = strip_tags($var);
		$var = htmlentities($var, ENT_QUOTES);
		return $var;
	}

	function sanitizeMySQL($conn, $var) 
	{
		$var = $conn->real_escape_string($var);
		$var = sanitizeString($var);
		return $var;
	}


	function random()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';

		for ($i = 0; $i < 8; $i++) 
		{
			$index = rand(0, strlen($characters) - 1);
			$randomString .= $characters[$index];
		}
		return $randomString;
	}

	$result->close();
	$stmt->close();
	$conn->close();
?>