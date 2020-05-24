<?php
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die('error');
    
    echo <<<_END
	<html><body>
		<form action="user.php" method="post"><pre>
		USER SIGNUP
        Username <input type="text" name="username">
        Email <input type="text" name="email">
		Password <input type="text" name="password">
		<input type="submit" value="Sign Up" name="signup">
		</pre></form>
_END;
    echo "</body></html>";

    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
        $username = sanitizeUsername($conn, $_POST['username']);
        $email = sanitizeMySQL($conn, $_POST['email']);
		$password = sanitizeMySQL($conn, $_POST['password']);
        if ($username === FALSE){
            echo "Invalid username. Please use only digits, letters, underscore, and dash";
        }
        else{
            createUser($conn, $username, $email, $password);
        }
    }

    echo <<<_END
	<html><body>
		<form action="user.php" method="post"><pre>
		USER LOGIN
		Username <input type="text" name="loginusername">
		Password <input type="text" name="loginpassword">
		<input type="submit" value="Log In" name="submit">
		</pre></form>
_END;
	echo "</body></html>";
	if(isset($_POST['loginusername']) && isset($_POST['loginpassword'])){
		$username = sanitizeMySQL($conn,$_POST['loginusername']);
        $password = sanitizeMySQL($conn,$_POST['loginpassword']);
        login($conn, $username, $password);
    }

    function login($conn, $username, $password){
        $stmt = $conn->prepare('SELECT * FROM users WHERE username=?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if(!$result) die("Error");
        if ($result->num_rows > 0){
            $row = $result->fetch_array(MYSQLI_ASSOC);
			$salt = $row["salt"];
            $hash = hash('ripemd128', "$salt$password");
            if($hash == $row["password"]){
                session_start();
                $_SESSION['username'] = $username;
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
		<form action="decryptoid.php" method="post"><pre>
		Access Granted Hit Button to Continue
		<input type="submit" value="Start" name="submit">
		</pre></form>
_END;
	echo "</body></html>";
	}
    
    function createUser($conn, $username, $email, $password){
        $stmt = $conn->prepare('INSERT INTO users VALUES'.'(?, ?, ?, ?)');
        $salt = random();
        $hash = hash('ripemd128', "$salt$password");
        $stmt->bind_param('ssss', $username, $email, $hash, $salt);
        $stmt->execute();

        if($stmt->affected_rows === 0)die("error");
        else echo("You have successfully signed up!<br>");
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
    
    function sanitizeUsername($conn, $var){
        $var = sanitizeMySQL($conn, $var);
        $allowedChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
        for($i = 0; $i < strlen($var); $i++){
            $letter = substr($var, $i, 1);
            if(!is_numeric(strpos($allowedChars, $letter))){
                return FALSE;
            }
        }
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