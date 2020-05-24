<?php    
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die('error');

    session_start();
    if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        destroy_session_and_data();
        echo "Welcome back $username.<br>";
        start($conn);
    }
    else if (isset($_POST['encrypt']) || isset($_POST['decrypt'])){
        start($conn);
    }
    else{
        echo <<<_END
	<html><body>
		<form action="user.php" method="post"><pre>
		Click Here to Login or Sign Up
		<input type="submit" value="Login/Sign Up" name="submit">
		</pre></form>
_END;
	echo "</body></html>";
    }
    function start($conn){
        echo <<<_END
        <html><body>
            <form action="decryptoid.php" method="post" enctype="multipart/form-data"><pre>
            Encrypt or Decrypt a File using Simple Substitution

            Key <input type="text" name="key">
            Plaintext/CipherText <input type="file" name="pctext" id="pctext"></pre>
            <input type="submit" value="Encrypt" name="encrypt">
            <input type="submit" value="Decrypt" name="decrypt">
        </form>
_END;

        if (isset($_POST['key']) && $_FILES){
            $key = sanitizeMySQL($conn, $_POST['key']);
            $pctext = sanitizeMySQL($conn, $_POST['pctext']);
            switch($_FILES['pctext']['type']){
                case 'text/plain' : $ext = 'txt'; break;
                default: $ext = ''; break;
            }
            if ($ext && is_numeric($key)){
                $fileName = $_FILES['pctext']['tmp_name'];
                $file = file_get_contents($fileName);
                $fileAfter = onlyLetters($conn, $file);
                add($conn, $fileAfter);
                encryptDecrypt($conn, $key, $fileAfter);
            }
            else if(!is_numeric($key)){
                echo "Key is not a numeric value";
            }
            else {
                echo "Uploaded file is not in txt format";
            }
        }
        echo "</body></html>";
    }

    function destroy_session_and_data(){
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
    function sanitizeString($var){
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var, ENT_QUOTES);
        return $var;
    }

    function sanitizeMySQL($conn, $var){
        $var = $conn->real_escape_string($var);
        $var = sanitizeString($var);
        return $var;
    }


    function add($conn, $file){
        $timestamp = date('Y-m-d H:i:s');
        $stmt = $conn->prepare('INSERT INTO inputs VALUES'.'(?,?)');
        $stmt->bind_param('ss', $file, $timestamp);
        $stmt->execute();
        if($stmt->affected_rows == 0) die("Error");
    }

    function onlyLetters($conn, $file){
        $allowedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
        for($i = 0; $i < strlen($file); $i++){
            $letter = substr($file, $i, 1);
            if(!is_numeric(strpos($allowedChars, $letter))){
                $file = str_replace($letter, "", $file);
            }
        }
        return $file;
    }

    function encryptDecrypt($conn, $key, $pctext){
        $newString = "";
        $lowercase = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n",
        "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"];
        $uppercase = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N",
        "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        if(isset($_POST['encrypt'])){
            for($i = 0; $i < strlen($pctext); $i++){
                $letter = substr($pctext, $i, 1);
                if(in_array($letter, $lowercase)){
                    $index = array_search($letter, $lowercase);
                    $newString = $newString.$lowercase[($index + $key) % 26];
                }
                else if(in_array($letter, $uppercase)){
                    $index = array_search($letter, $uppercase);
                    $newString = $newString.$uppercase[($index + $key) % 26];
                }
                else {
                    $newString = $newString." ";
                }
            }
        }
        else if(isset($_POST['decrypt'])){
            for($i = 0; $i < strlen($pctext); $i++){
                $letter = substr($pctext, $i, 1);
                if(in_array($letter, $lowercase)){
                    $index = array_search($letter, $lowercase);
                    if ($index - $key >= 0){
                        $newString = $newString.$lowercase[$index - $key];
                    }
                    else{
                        $posIndex = ($index - $key) * -1;
                        $newString = $newString.$lowercase[25 - ($posIndex % 26)];
                    }
                }
                else if(in_array($letter, $uppercase)){
                    $index = array_search($letter, $uppercase);
                    if ($index - $key >= 0){
                        $newString = $newString.$uppercase[$index - $key];
                    }
                    else{
                        $posIndex = ($index - $key) * -1;
                        $newString = $newString.$uppercase[25 - ($posIndex % 26)];
                    }
                }
                else {
                    $newString = $newString." ";
                }
            }
        }
        echo $newString;
    }

    $stmt->close();
	$conn->close();
?>