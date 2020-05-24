<?php
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die('error');

    echo <<<_END
    <html><body>
        <form action="midterm2user.php" method="post" enctype="multipart/form-data"><pre>
        Putative Infected File <input type="file" name="userfile" id="userfile">
        <input type="submit" value="ADD FILE" name="submit">
        </pre></form>
_END;
    echo "</body></html>";

    if ($_FILES){
        $file = sanitizeMySQL($conn, $_POST['userfile']);
        $filename = $_FILES['userfile']['tmp_name'];
        checkInfected($conn, $filename);
    }

    $result->close();
    $stmt->close();
    $conn->close();

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

    function checkInfected($conn, $filename){
        $fp = fopen($filename, 'r');
        $count = 0;
        for ($i = 0; $i < SEEK_END; $i++){
            fseek($fp, $i);
            $malwareCheck = fread($fp, 20);
            $stmt = $conn->prepare("SELECT * FROM malwareTable WHERE malware=?");
            $stmt->bind_param('s', $malwareCheck);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!$result) die("Error");
            if ($result->num_rows > 0){
                $count++;
                echo "This file is infected!";
                break;
            }
        }
        if ($count == 0){
            echo "This file is not infected!";
        }
        fclose($fp);
    }
?>