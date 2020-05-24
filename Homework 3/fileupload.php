<?php   
    // Functions
    function adjacentFive($digits){
        $max = 0;
        for ($i = 0; $i < strlen($digits) - 4; $i++){
            if(is_numeric(substr($digits, $i, 5))){
                $product = substr($digits, $i, 1) * substr($digits, $i+1, 1) * substr($digits, $i+2, 1)
                * substr($digits, $i+3, 1) * substr($digits, $i+4, 1);
                if ($max < $product){
                    $adjacent = substr($digits, $i, 5); 
                    $max = $product;
                }
            }
            else{
                return "File has a non-numeric digit";
            }
        }
        return $adjacent;
    }

    function largestProduct($adjacent){
        $product = $adjacent[0] * $adjacent[1] * $adjacent[2] * $adjacent[3] * $adjacent[4];
        return $product;
    }

    function factorialSingle($digit){
        if ($digit <= 1){
            return 1;
        }
        else{
            return $digit * factorialSingle($digit - 1);
        }
    }
    
    function factorialProduct($product){
        $array = array_map('intval', str_split($product));
        $factorialTotal = 0;
        foreach($array as $number){
            $factorialTotal += factorialSingle($number);
        }
        return $factorialTotal;
    }
    // HTML
    echo<<<_END
        <html>
            <body>
                <form action="fileupload.php" method="post" enctype="multipart/form-data">
                    Select file to upload
                    <input type="file" name="fileUploaded" id="fileUploaded">
                    <input type="submit" value = "Upload File" name="submit">
                </form>
_END;
    // PHP
    $NECESSARY_DIGITS = 1000;
    if ($_FILES){
        switch($_FILES['fileUploaded']['type']){
            case 'text/plain' : $ext = 'txt'; break;
            default: $ext = ''; break;
        }
        if ($ext){
            $name = $_FILES['fileUploaded']['tmp_name'];
            $file = file_get_contents($name);
            // Sanitize to make one line
            $file = str_replace(["\n", " "], "", $file);
            if (strlen($file) == $NECESSARY_DIGITS){
                $adjacent5 = adjacentFive($file);
                if(strlen($adjacent5) == 5){
                    $adjacent5Formula = $adjacent5[0]."*".$adjacent5[1]."*".$adjacent5[2]."*".$adjacent5[3].
                    "*".$adjacent5[4];
                    $product = largestProduct($adjacent5);
                    echo "The Largest Product is: ".$adjacent5Formula ." = ".$product."<br>";
                    echo "The Facotrial of the Largest Product is: ".factorialProduct($product)."<br>";
                }
                else{
                    echo $adjacent5."<br>";
                }
            }
            else{
                echo "The file does not contain 1000 digits <br>";
            }
        }
        else{
            echo "Uploaded file not in txt format<br>";
        }
    }
    echo "</body></html>";

    echo"<br>--------TEST--------<br>";
    // Tester
    function test(){
        $adjacent9 = adjacentFive("009111100");
        echo "Adjacent 5 with largest product is - Expected: 91111, Actual: ".$adjacent9."<br>";
        $product9 = largestProduct($adjacent9);
        echo "Largest Product from previous test is - Expected: 9, Actual: ".$product9."<br>";
        $factorial5 = factorialSingle(5);
        echo "Factorial of 5 - Expected: 120, Actual: ".$factorial5."<br>";
        $addFactorial1234 = factorialProduct(1234);
        echo "Added up Factorials of 1234 - Expected: 33, Actual: ".$addFactorial1234."<br>";
    }
    test();
?>