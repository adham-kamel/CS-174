<?php
    require_once 'login.php';
    // PHP
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die("Error");

    echo <<<_END
    <html><body>
        <form action="addSearch.php" method="post"><pre>
        Advisor <input type="text" name="Advisor">
        Student Name <input type="text" name="Student">
        Student ID <input type="text" name="SID">
        Class Code <input type="text" name="classID">
        <input type="submit" value="ADD CLASS" name="submit">
    </pre></form>
_END;
    if (isset($_POST['Advisor']) && isset($_POST['Student']) && isset($_POST['SID']) && 
    isset($_POST['classID'])){
        $advisor = get_post($conn, 'Advisor');
        $student = get_post($conn, 'Student');
        $sid = get_post($conn, 'SID');
        $classID = get_post($conn, 'classID');
        add($conn, $advisor, $student, $sid, $classID);
    }
    echo "</body></html>";

    echo <<<_END
        <form action="addSearch.php" method="post">
        Search Advisor <input type="text" name="SearchAdv">
        <input type="submit" value="SEARCH ADVISOR" name="submit">
        </form>
_END;

    if(isset($_POST['SearchAdv'])){
        $searchAdv = get_post($conn, 'SearchAdv');
        searchAdvisor($conn, $searchAdv);
    }

    $result->close();
    $stmt->close();
    $conn->close();


    function get_post($conn, $var){
        return $conn->real_escape_string($_POST[$var]);
    }

    function add($conn, $advisor, $student, $sid, $classID){
        $stmt = $conn->prepare('INSERT INTO class VALUES'.'(?,?,?,?)');
        $stmt->bind_param('ssis', $advisor, $student, $sid, $classID);
        $stmt->execute();
        if($stmt->affected_rows == 0) die("Error");
    }

    function searchAdvisor($conn, $searchAdv){
        $stmt = $conn->prepare("SELECT * FROM class WHERE Advisor=?");
        $stmt->bind_param('s', $searchAdv);
        $stmt->execute();
        $result = $stmt->get_result();
        if(!$result) die("Error");
        $rows = $result->num_rows;
        displaySearch($conn, $rows, $result);
        
    }

    function displaySearch($conn, $rows, $result){
        for ($i = 0; $i < $rows; $i++){
            $result->data_seek($i);
            $row = $result->fetch_array(MYSQLI_NUM);
            echo <<<_END
            <pre>
                Advisor: $row[0], Student Name: $row[1], Student ID: $row[2], Class ID: $row[3]
            </pre>
_END;
        }
    }
?>