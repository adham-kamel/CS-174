<?php
    require_once 'login.php';
    // PHP
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die("Error");
    echo <<<_END
    <html><body>
        <form action="midterm2admin.php" method="post" enctype="multipart/form-data"><pre>
        Name <input type="text" name="Name">
        Malware File <input type="file" name="malware" id="malware">
        <input type="submit" value="ADD FILE" name="submit">
    </pre></form>
_END;

    if (isset($_POST['Name']) && $_FILES){
        $name = sanitizeMySQL($conn, $_POST['Name']);
        $content = sanitizeMySQL($conn, $_POST['malware']);
        $contentName = $_FILES['malware']['tmp_name'];
        $fp = fopen($contentName, 'r');
        $malwareBytes = fread($fp, 20);
        fclose($fp);
        add($conn, $malwareBytes, $name);
    }
    echo "</body></html>";

    $query = "SELECT * FROM malwareTable";
    $result = $conn->query($query);
    if(!$result) die("Error");
    $rows = $result->num_rows;
    for ($i = 0; $i < $rows; $i++){
        $result->data_seek($i);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo <<<_END
        <pre>
            Malware Bytes $row[0]
            Name $row[1]
        </pre>
_END;
    }
    $query->close();
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

    function add($conn, $malware, $name){
        $stmt = $conn->prepare('INSERT INTO malwareTable VALUES'.'(?,?)');
        $stmt->bind_param('ss', $malware, $name);
        $stmt->execute();
        if($stmt->affected_rows == 0) die("Error");
    }
?>