<?php
    require_once 'login.php';
    // PHP
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die("Error");

    if(isset($_POST['delete']) && isset($_POST['id'])){
        $id = get_post($conn, 'id');
        $query = "DELETE FROM contents WHERE id='$id'";
        $result = $conn->query($query);
        if(!$result) echo "DELETE FAILED";
    }


    echo <<<_END
    <html><body>
        <form action="uploadMySQL.php" method="post" enctype="multipart/form-data"><pre>
        Name <input type="text" name="Name">
        Content <input type="file" name="Content" id="Content">
        <input type="submit" value="ADD FILE" name="submit">
    </pre></form>
_END;

    if (isset($_POST['Name']) && $_FILES){
        $name = get_post($conn, 'Name');
        $content = get_post($conn, 'Content');
        switch($_FILES['Content']['type']){
            case 'text/plain' : $ext = 'txt'; break;
            default: $ext = ''; break;
        }
        if ($ext){
            $contentName = $_FILES['Content']['tmp_name'];
            $file = file_get_contents($contentName);
            $query = "INSERT INTO contents VALUES"."('$name', '$file', NULL)";
            $result = $conn->query($query);
            if (!$result) echo "Error";
        }
        else {
            echo "Uploaded file is not in txt format";
        }
    }
    echo "</body></html>";

    $query = "SELECT * FROM contents";
    $result = $conn->query($query);
    if(!$result) die("Error");
    $rows = $result->num_rows;
    for ($i = 0; $i < $rows; $i++){
        $result->data_seek($i);
        $row = $result->fetch_array(MYSQLI_NUM);
        echo <<<_END
        <pre>
            Name $row[0]
            Content $row[1]
        </pre>
        <form action="uploadMySQL.php" method="post">
        <input type="hidden" name="delete" value="yes">
        <input type="hidden" name="id" value="$row[2]">
        <input type="submit" value="DELETE RECORD"></form>
_END;
    }

    $result->close();
    $conn->close();

    function get_post($conn, $var){
        return $conn->real_escape_string($_POST[$var]);
    }
?>